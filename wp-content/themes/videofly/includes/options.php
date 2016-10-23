<?php
/**
 * Export/Import Theme options
 */
function videofly_impots_options()
{
	//if not administrator, kill WordPress execution and provide a message
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	parse_str($_SERVER['QUERY_STRING'], $params);

	if (preg_match("/^.*\/wp-admin\/admin.php$/i", $_SERVER['SCRIPT_NAME'], $matches)) {
		if (array_key_exists('page', $params) && array_key_exists('tab', $params) ) {
			if ($params['page'] === 'videofly' && $params['tab'] === 'save_options') {
				if (isset($_POST['encoded_options'])) {

					$import = videofly_impots_decoded_options($_POST['encoded_options']);

					if ($import) {
						$status = '&updated=true';
					} else {
						$status = '&updated=false';
					}

					wp_redirect( esc_url( admin_url( 'admin.php?page=videofly&tab=impots_options' . $status ) ) );
				}
			}
		}
	}
}

add_action('admin_init', 'videofly_impots_options');

function videofly_load_patterns()
{
	//if not administrator, kill WordPress execution and provide a message
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	parse_str($_SERVER['QUERY_STRING'], $params);

	if (preg_match("/^.*\/wp-admin\/admin.php$/i", $_SERVER['SCRIPT_NAME'], $matches)) {
		if (array_key_exists('page', $params) && array_key_exists('tab', $params) ) {
			if ($params['page'] === 'videofly' && $params['tab'] === 'load_patterns') {
				require_once(get_template_directory() .'/includes/patterns.php');
				die();
			}
		}
	}
}

add_action('admin_init', 'videofly_load_patterns');

function videofly_impots_decoded_options($encoded) {

	if( !function_exists('ts_enc_string') ) {
	    echo ('The plugin "Touchsize Custom Posts" is required.');
	    return;
	}

	$options = unserialize(ts_enc_string($encoded, 'decode'));

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

function videofly_exports_options() {

	if( !function_exists('ts_enc_string') ) {
	    echo ('The plugin "Touchsize Custom Posts" is required.');
	    return;
	}

	$export = array();

	$expots_options = array(
		'videofly_general',
		'videofly_image_sizes',
		'videofly_layout',
		'videofly_colors',
		'videofly_styles',
		'videofly_typography',
		'videofly_single_post',
		'videofly_page',
		'videofly_social',
		'inline_style',
		'videofly_sidebars',
		'videofly_header',
		'videofly_header_templates',
		'videofly_header_template_id',
		'videofly_footer',
		'videofly_footer_templates',
		'videofly_footer_template_id',
		'videofly_footer_template_id',
		'videofly_page_template_id',
		'videofly_theme_advertising',
		'videofly_theme_update'
	);

	foreach ($expots_options as $option) {
		$export[$option] = get_option($option, array());
	}

	return ts_enc_string(serialize($export));
}

function register_my_menu() {

  register_nav_menu('primary', esc_html__( 'Primary Menu', 'videofly' ));
}

add_action( 'init', 'register_my_menu' );

/**
 * Generate menu for Videofly Theme
 */
function videofly_create_menu()
{
	add_theme_page(
		'Videofly Options',
		'Theme Options',
		'edit_theme_options',
		'videofly',
		'videofly_display_menu_page');
	add_theme_page('Header',
					esc_html__('Header', 'videofly'),
					'edit_theme_options',
					'videofly_header',
					'videofly_header');
	add_theme_page('Footer',
					esc_html__('Footer', 'videofly'),
					'edit_theme_options',
					'videofly_footer',
					'videofly_footer');
}

add_action( 'admin_menu', 'videofly_create_menu' );

function videofly_template_modals($location = 'header', $template_id = 'default', $template_name = 'Default') {
	ob_start();
    ob_clean();
    	wp_editor('', 'ts_editor_id', array(
    			'textarea_name' => 'ts_name_textarea',
    			'wpautop' => false,
    			'tinymce' => array(
    				'wpautop' => false,
    				'elements' => 'ts_editor_id',
    				'apply_source_formatting' => false,
    			)
    		)
    	);
    $editor_code = ob_get_clean();
	return '<table>
		<tr>
			<td style="width: 500px">
				<p>
					<input id="ts-blank-template" data-location="'.esc_attr($location).'" data-toggle="modal" data-target="#ts-confirmation" type="button" name="submit" class="button-primary" value="'.esc_html__('Clear layout', 'videofly') . '" />

					<input id="ts-save-as-template" data-location="'.esc_attr($location).'" data-toggle="modal" data-target="ts-save-template-modal" type="button" name="submit" class="button-primary" value="' . esc_html__('Save as...', 'videofly') . '" />

					<input id="ts-load-template-button" data-location="'.esc_attr($location).'" type="button" name="submit" class="button-primary" value="'. esc_html__('Load template', 'videofly') . '" />
				</p>
				<!-- Blank template modal -->
				<div class="modal ts-modal fade" id="ts-blank-template-modal" tabindex="-1" role="dialog" aria-labelledby="blank-template-modal" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="blank-template-modal">' . esc_html__('Blank template', 'videofly') . '</h4>
							</div>
							<div class="modal-body">
								<h5>' . esc_html__('Template name', 'videofly') . '</h5>
								<input type="text" name="template_name" value="" id="ts-blank-template-name"/>
							</div>
					      	<div class="modal-footer">
					        	<button type="button" class="button-primary" data-dismiss="modal">' . esc_html__('Close', 'videofly') . ' </button>
								<button type="button" class="button-primary" data-location="' . esc_attr($location) . '" id="ts-save-blank-template-action">' . esc_html__('Save', 'videofly') .'</button>
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
								<h4 class="modal-title" id="blank-modal-confirmation-label">' . esc_html__('Are you sure?', 'videofly') . '</h4>
							</div>
							<div class="modal-body">
								<p>' . esc_html__('The layout you currently have on the page will be removed. It will only be saved in the database only after you click Save Layout (Publish) button.', 'videofly') . '</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="button-primary ts-modal-confirm">' . esc_html__('Yes', 'videofly') . '</button>
								<button type="button" class="button-primary ts-modal-decline" data-dismiss="modal">' . esc_html__('No', 'videofly') .' </button>
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
								<h4 class="modal-title" id="save-template-modal-label">' . esc_html__('Save template', 'videofly') . '</h4>
							</div>
							<div class="modal-body">
								<h5>' . esc_html__('Template name', 'videofly') . ':</h5>
								<input type="text" name="template_name" value="" id="ts-save-template-name"/>
							</div>
							<div class="modal-footer">
								<button type="button" class="button-primary" data-dismiss="modal">' . esc_html__('Close', 'videofly') . '</button>
								<button type="button" class="button-primary" data-location="'.esc_attr($location).'" id="ts-save-as-template-action">' . esc_html__('Save', 'videofly') . '</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Load template modal -->
				<div class="modal ts-modal fade" id="ts-load-template" tabindex="-1" role="dialog" aria-labelledby="load-template-modal-label" aria-hidden="true">
					<div class="modal-dialog">
					    <div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="load-template-modal-label">' . esc_html__('Load template', 'videofly') . '</h4>
							</div>
							<div class="modal-body">
								<h5>' . esc_html__('Select template', 'videofly') . ':</h5>
								<table id="ts-layout-list">

								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="button-primary" data-dismiss="modal">'.esc_html__('Cancel', 'videofly') . '</button>
								<button type="button" class="button-primary" data-location="'.esc_attr($location).'" id="ts-load-template-action">' . esc_html__('Load', 'videofly') . '</button>
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
								<button type="button" class="ts-change-element">' . esc_html__('Change element type', 'videofly') . '</button>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="ts-builder-elements-modal-label" data-elements-title="' . esc_html__('Builder elements', 'videofly') . '" data-element-title="' . esc_html__('Builder element', 'videofly') . '"></h4>
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
				<div class="modal ts-modal fade" id="ts-builder-elements-modal-editor" tabindex="-1" role="dialog" aria-labelledby="ts-builder-elements-modal-editor-label" aria-hidden="true">
					<div class="modal-dialog">
					    <div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="ts-builder-elements-editor-modal-label">' . esc_html__('Text element', 'videofly') . '</h4>
							</div>
							<div class="modal-body">
								<table width="100%" cellpadding="10">
								    <tr>
								        <td>
								            <label for="text-admin-label">' . esc_html__('Admin label','videofly') . ':</label>
								        </td>
								        <td>
								           <input type="text" id="text-admin-label" name="text-admin-label" />
								        </td>
								    </tr>
								    <tr class="ts-select-animation">
								        <td>
								            ' . esc_html__('Special effect','videofly') . '
								        </td>
								        <td>
								           '. vdfAllAnimations('text-effect', 'effect') .'
								        </td>
								    </tr>
								    <tr class="ts-select-delay">
								        <td>
								           ' . esc_html__('Delay','videofly') . '
								        </td>
								        <td>
								            '. vdfAllAnimations('text-delay', 'delay') .'
								        </td>
								    </tr>
								    <tr>
								        <td>' . esc_html__('Add your text here','videofly') . ':</td><td>'
									. $editor_code .
									'</td>
									</tr>
									<tr>
										<td>
											<div class="button-primary ts-save-editor save-element">' . esc_html__('Save', 'videofly') . '</div>
										</td>
										<td></td>
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
					<strong> ' . esc_html__('Template name', 'videofly') . ' :</strong> <span id="ts-template-name">'.wp_kses($template_name, array()).'</span>
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
function videofly_header()
{
?>
	<div class="wrap">
		<div class="wrap-red-templates">
			<p><h2><?php esc_html_e('Header', 'videofly') ?></h2></p>
			<?php
				$template_id = Template::get_template_info('header', 'id');
				$template_name = Template::get_template_info('header', 'name');
			 	echo videofly_template_modals( 'header', $template_id, $template_name );
			?>
			<br/><br/>
			<?php vdf_layout_wrapper(Template::edit('header')); ?>
			<input id="save-header-footer" data-location="header" type="submit" name="submit" id="submit" class="button-primary" value="<?php esc_html_e('Save Changes', 'videofly') ?>"/>
		</div>
		<div id="ts_editor_default" style="display:none"><?php wp_editor('','ts_editor_footer',array('textarea_name' => 'ts_texatarea_name_footer','drag_drop_upload' => true));  ?></div>
	</div>
<?php
}

/**
 * Edit footer
 */
function videofly_footer()
{
?>
	<div class="wrap">
		<div class="wrap-red-templates">
			<p><h2><?php esc_html_e('Footer', 'videofly') ?></h2></p>
			<?php
				$template_id = Template::get_template_info('footer', 'id');
				$template_name = Template::get_template_info('footer', 'name');
				echo videofly_template_modals( 'footer', $template_id, $template_name );
			?>
			<br/><br/>

			<?php vdf_layout_wrapper(Template::edit('footer')); ?>
			<input id="save-header-footer" data-location="footer" type="submit" name="submit" id="submit" class="button-primary" value="<?php esc_html_e('Save Changes', 'videofly') ?>"/>
		</div>
		<div id="ts_editor_default" style="display:none"><?php wp_editor('','ts_editor_footer',array('textarea_name' => 'ts_texatarea_name_footer'));  ?></div>
	</div>
<?php
}

function videofly_display_menu_page( $active_tab = '')
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

			} else if ( $active_tab === 'inline_style' ) {

				$active_tab = 'inline_style';

			} else if ( $active_tab === 'sidebars' ) {

				$active_tab = 'sidebars';

			} else if ( $active_tab === 'impots_options' ) {

				$active_tab = 'impots_options';

			} else if ( $active_tab === 'red_area' ) {

				$active_tab = 'red_area';

			} else if ( $active_tab === 'theme_advertising' ) {

				$active_tab = 'theme_advertising';

			} else if ( $active_tab === 'support' ) {

				$active_tab = 'support';

			} else {

				$active_tab = 'general';
			}
		?>
		<div id="red-wprapper">
			<div id="red-menu">
				<ul id="theme-setting">
					<li>
						<a href="?page=videofly&tab=general" class="<?php echo ($active_tab == 'general') ? 'selected-tab' : ''; ?>">
							<i class="icon-settings"></i>
							<span><?php esc_html_e( 'General', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=styles" class="<?php echo ($active_tab == 'styles') ? 'selected-tab' : ''; ?>">
							<i class="icon-code"></i>
							<span><?php esc_html_e( 'Styles', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=colors" class="<?php echo ($active_tab == 'colors') ? 'selected-tab' : ''; ?>">
							<i class="icon-palette"></i>
							<span><?php esc_html_e( 'Colors', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=image_sizes" class="<?php echo ($active_tab == 'image_sizes') ? 'selected-tab' : ''; ?>">
							<i class="icon-image-size"></i>
							<span><?php esc_html_e( 'Image sizes', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=layout" class="<?php echo ($active_tab == 'layout') ? 'selected-tab' : ''; ?>">
							<i class="icon-layout"></i>
							<span><?php esc_html_e( 'Layout', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=typography" class="<?php echo ($active_tab == 'typography') ? 'selected-tab' : ''; ?>">
							<i class="icon-edit"></i>
							<span><?php esc_html_e( 'Typography', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=single" class="<?php echo ($active_tab == 'single') ? 'selected-tab' : ''; ?>">
							<i class="icon-post"></i>
							<span><?php esc_html_e( 'Single post', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=page_settings" class="<?php echo ($active_tab == 'page_settings') ? 'selected-tab' : ''; ?>">
							<i class="icon-page"></i>
							<span><?php esc_html_e( 'Page settings', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=social" class="<?php echo ($active_tab == 'social') ? 'selected-tab' : ''; ?>">
							<i class="icon-social"></i>
							<span><?php esc_html_e( 'Social', 'videofly' ); ?></span>
						</a>
					</li>
					<?php if( is_plugin_active('ts-custom-posts/ts-custom-posts.php') ): ?>
						<li>
							<a href="?page=videofly&tab=inline_style" class="<?php echo ($active_tab == 'inline_style') ? 'selected-tab' : ''; ?>">
								<i class="icon-code"></i>
								<span><?php esc_html_e( 'Custom CSS', 'videofly' ); ?></span>
							</a>
						</li>
					<?php endif ?>
					<li>
						<a href="?page=videofly&tab=sidebars" class="<?php echo ($active_tab == 'sidebars') ? 'selected-tab' : ''; ?>">
							<i class="icon-sidebar"></i>
							<span><?php esc_html_e( 'Sidebars', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=impots_options" class="<?php echo ($active_tab == 'impots_options') ? 'selected-tab' : ''; ?>">
							<i class="icon-import"></i>
							<span><?php esc_html_e( 'Import options', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=theme_advertising" class="<?php echo ($active_tab == 'theme_advertising') ? 'selected-tab' : ''; ?>">
							<i class="icon-dollar"></i>
							<span><?php esc_html_e( 'Advertising', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=theme_update" class="<?php echo ($active_tab == 'theme_update') ? 'selected-tab' : ''; ?>">
							<i class="icon-download"></i>
							<span><?php esc_html_e( 'Theme update', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=red_area" class="<?php echo ($active_tab == 'red_area') ? 'selected-tab' : ''; ?>">
							<i class="icon-attention"></i>
							<span><?php esc_html_e( 'Red Area', 'videofly' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videofly&tab=support" class="<?php echo ($active_tab == 'support') ? 'selected-tab' : ''; ?>">
							<i class="icon-support"></i>
							<span><?php esc_html_e( 'Support', 'videofly' ); ?></span>
						</a>
					</li>
				</ul>
			</div>
			<div id="red-options">
				<div class="theme-name">
					<h3>Videofly</h3>
					<h3>TouchSize</h3>
				</div>
				<?php if ($active_tab !== 'impots_options' ): ?>
				<form method="post" data-table="<?php echo vdf_var_sanitize($active_tab); ?>" action="options.php" enctype="multipart/form-data">
				<?php endif ?>
					<?php
						if ( $active_tab === 'general' ) {

							settings_fields( 'videofly_general' );
							do_settings_sections( 'videofly_general' );

						} else if ( $active_tab === 'styles' ) {

							settings_fields( 'videofly_styles' );
							do_settings_sections( 'videofly_styles' );

						} else if ( $active_tab === 'colors' ) {

							settings_fields( 'videofly_colors' );
							do_settings_sections( 'videofly_colors' );

						} else if ( $active_tab === 'image_sizes' ) {

							settings_fields( 'videofly_image_sizes' );
							do_settings_sections( 'videofly_image_sizes' );

						} else if ( $active_tab === 'layout' ) {

							settings_fields( 'videofly_layout' );
							do_settings_sections( 'videofly_layout' );

						} else if ( $active_tab === 'typography' ) {

							settings_fields( 'videofly_typography' );
							do_settings_sections( 'videofly_typography' );

						} else if ( $active_tab === 'single' ) {

							settings_fields( 'videofly_single_post' );
							do_settings_sections( 'videofly_single_post' );

						} else if ( $active_tab === 'page_settings' ) {

							settings_fields( 'videofly_page' );
							do_settings_sections( 'videofly_page' );

						} else if ( $active_tab === 'social' ) {

							settings_fields( 'videofly_social' );
							do_settings_sections( 'videofly_social' );

						} else if ( $active_tab === 'inline_style' ) {

							settings_fields( 'inline_style' );
							do_settings_sections( 'inline_style' );

						} else if ( $active_tab === 'sidebars' ) {

							settings_fields( 'videofly_sidebars' );
							do_settings_sections( 'videofly_sidebars' );

						} else if ( $active_tab === 'theme_advertising' ) {

							settings_fields( 'videofly_theme_advertising' );
							do_settings_sections( 'videofly_theme_advertising' );

						} else if ( $active_tab === 'theme_update' ) {

							settings_fields( 'videofly_theme_update' );
							do_settings_sections( 'videofly_theme_update' );

						}  else if ( $active_tab === 'impots_options' ) {

							settings_fields( 'videofly_impots_options' );
							do_settings_sections( 'videofly_impots_options' );

						} else if ( $active_tab === 'red_area' ) {

							settings_fields( 'videofly_red_area' );
							do_settings_sections( 'videofly_red_area' );

						} else if ( $active_tab === 'support' ) {

							settings_fields( 'videofly_support' );
							do_settings_sections( 'videofly_support' );

						}

					if ( $active_tab != 'sidebars' && $active_tab != 'red_area' && $active_tab != 'impots_options' && $active_tab != 'support' ) {
						submit_button(esc_html__('Save changes','videofly'), 'button', 'ts_submit_button');
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
function videofly_initialize_general_options()
{
	//delete_option('videofly_general');
	if ( false === get_option( 'videofly_general' ) ) {

		add_option( 'videofly_general', array(
			'featured_image_in_post' => 'Y',
			'enable_lightbox'        => 'Y',
			'enable_imagesloaded'    => 'N',
			'comment_system'         => 'default',
			'show_wp_admin_bar'      => 'Y',
			'enable_sticky_menu'     => 'Y',
			'enable_mega_menu'     	 => 'N',
			'sticky_menu_bg_color'	 => '#FFFFFF',
			'sticky_menu_text_color' => '#444444',
			'custom_javascript'		 => '',
			'enable_preloader'		 => 'N',
			'onepage_website'		 => 'N',
			'facebook_id'			 => '',
			'grid_excerpt'			 => 260,
			'list_excerpt'			 => 600,
			'bigpost_excerpt'	     => 260,
			'timeline_excerpt'	     => 260,
			'featured_area_excerpt'	 => 160,
			'enable_facebook_box'    => 'N',
			'facebook_name' 		 => '',
			'like'                   => 'y',
			'like_icon'              => 'heart',
			'dislike_icon'           => 'heart-broken',
			'text_like'              => 'likes',
			'text_dislike'           => 'dislikes',
			'mode_display_menu'      => 'ts-orizontal-menu',
			'slug_video_taxonomy'    => 'videos_categories',
			'slug_video'             => 'video',
			'slug_portfolio_taxonomy'=> 'portfolio-categories',
			'slug_portfolio'         => 'portfolio',
			'slug_event_taxonomy'    => 'event-categories',
			'slug_event'             => 'event',
			'slug_gallery_taxonomy'  => 'gallery-categories',
			'slug_gallery'           => 'gallery',
			'show_description'       => 'y',
			'show_icons'             => 'y',
			'right_click'            => 'n',
			'post_status_user'       => 'pending',
			'user_tab_post'          => array('url' => '', 'upload' => '', 'embed' => '')
		));

	} // end if

	// Register a section
	add_settings_section(
		'general_settings_section',
		esc_html__( 'General Options', 'videofly' ),
		'videofly_general_options_callback',
		'videofly_general'
	);

	add_settings_field(
		'enable_preloader',
		esc_html__( 'Enable preloader for website?', 'videofly' ),
		'toggle_enable_preloader_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( "If you want to add a preloader to your website, you can activate it only for your frontpage, for the whole website or disable it.", 'videofly' )
		)
	);

	add_settings_field(
		'onepage_website',
		esc_html__( 'Enable the onepage layout', 'videofly' ),
		'toggle_onepage_website_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( "If you enable this, you'll activate the smooth scrolling for the menus in onepage layout. Do not forget to create links with the row names and set them in your menu. For more info check the documentation.", 'videofly' )
		)
	);

	add_settings_field(
		'featured_image_in_post',
		esc_html__( 'Display featured image in post?', 'videofly' ),
		'toggle_featured_image_in_post_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( "Use this to hide or show the featured image in posts.", 'videofly' )
		)
	);

	add_settings_field(
		'enable_lightbox',
		esc_html__( 'Enable lightbox?', 'videofly' ),
		'toggle_enable_lightbox_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( "Enable this if you want your featured images on single pages to have lightbox available", 'videofly')
		)
	);

	add_settings_field(
		'enable_imagesloaded',
		esc_html__( 'Want to use imagesloaded?', 'videofly' ),
		'toggle_enable_imagesloaded_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( "Help loading site with a relatively higher speed.", 'videofly')
		)
	);

	add_settings_field(
		'comment_system',
		esc_html__( 'Which comment system you want to use?', 'videofly' ),
		'toggle_comment_system_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'Select which type of comments do you want to use.', 'videofly' )
		)
	);

	add_settings_field(
		'enable_facebook_box',
		esc_html__( 'Facebook modal box page', 'videofly' ),
		'facebook_page_modal_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'Add the page modal box in the site.', 'videofly' )
		)
	);

	add_settings_field(
		'show_wp_admin_bar',
		esc_html__( 'Show wordpress admin bar?', 'videofly' ),
		'toggle_show_wp_admin_bar_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'This options disables the wordpress admin bar when logged.', 'videofly' )
		)
	);

	add_settings_field(
		'enable_sticky_menu',
		esc_html__( 'Enable sticky menu', 'videofly' ),
		'toggle_enable_sticky_menu_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'Enable sticky menu', 'videofly' )
		)
	);

	add_settings_field(
		'enable_mega_menu',
		esc_html__( 'Enable mega menu', 'videofly' ),
		'toggle_enable_mega_menu_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( "If you want to add a mega menu to your website, you can activate it.", 'videofly' )
		)
	);

	add_settings_field(
		'like',
		esc_html__( 'Enable likes', 'videofly' ),
		'toggle_like_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( "If you want to add a likes to your website, you can activate it.", 'videofly' )
		)
	);

	add_settings_field(
		'like_icon',
		esc_html__( 'Select your icon for like', 'videofly' ),
		'toggle_like_icon_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( "You can select your icon for likes.", 'videofly' )
		)
	);

	add_settings_field(
		'dislike_icon',
		esc_html__( 'Select your icon for dislike', 'videofly' ),
		'toggle_dislike_icon_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( "You can select your icon for dislikes.", 'videofly' )
		)
	);

	add_settings_field(
		'text_like',
		esc_html__( 'Write text for likes', 'videofly' ),
		'toggle_text_like_callback',
		'videofly_general',
		'general_settings_section',
		array('')
	);

	add_settings_field(
		'text_dislike',
		esc_html__( 'Write text for dislike', 'videofly' ),
		'toggle_text_dislike_callback',
		'videofly_general',
		'general_settings_section',
		array('')
	);

	add_settings_field(
		'generate_likes',
		esc_html__( 'You can add the likes to posts', 'videofly' ),
		'toggle_generate_likes_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( "You can generate likes in your posts.", 'videofly' )
		)
	);

	add_settings_field(
		'custom_javascript',
		esc_html__( 'Custom JavaScript code', 'videofly' ),
		'toggle_custom_javascript_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'Google analytics or any other scripts you have', 'videofly' )
		)
	);


	add_settings_field(
		'grid_excerpt',
		esc_html__( 'Grid view excerpt size', 'videofly' ),
		'toggle_grid_excerpt_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'If you want to shorten or use more characters for your grid articles change the number here', 'videofly' )
		)
	);

	add_settings_field(
		'list_excerpt',
		esc_html__( 'List view excerpt size', 'videofly' ),
		'toggle_list_excerpt_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'If you want to shorten or use more characters for your list articles change the number here', 'videofly' )
		)
	);

	add_settings_field(
		'bigpost_excerpt',
		esc_html__( 'Big posts view excerpt size', 'videofly' ),
		'toggle_bigpost_excerpt_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'If you want to shorten or use more characters for your big post articles change the number here', 'videofly' )
		)
	);

	add_settings_field(
		'timeline_excerpt',
		esc_html__( 'Timeline view excerpt size', 'videofly' ),
		'toggle_timeline_excerpt_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'If you want to shorten or use more characters for your list articles change the number here', 'videofly' )
		)
	);

	add_settings_field(
		'featured_area_excerpt',
		esc_html__( 'Featured area excerpt size', 'videofly' ),
		'toggle_featured_area_excerpt_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'If you want to shorten or use more characters for your featured area articles change the number here', 'videofly' )
		)
	);

	add_settings_field(
		'right_click',
		esc_html__( 'Disable right click', 'videofly' ),
		'toggle_right_click_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'If you want to disable right click on the page set "yes"', 'videofly' )
		)
	);

	add_settings_field(
		'post_status_user',
		esc_html__( "Set status for users's posts", "videofly" ),
		'toggle_post_status_user_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( 'Default frontend submitted posts status', 'videofly' )
		)
	);

	add_settings_field(
		'user_tab_post',
		esc_html__( 'Choose type to add video in add post user', 'videofly' ),
		'toggle_user_tab_post_callback',
		'videofly_general',
		'general_settings_section',
		array(
			esc_html__( '', 'videofly' )
		)
	);

	if( is_plugin_active('ts-custom-posts/ts-custom-posts.php') ){
		add_settings_field(
			'slug_video',
			esc_html__( 'Change custom post video slug', 'videofly' ),
			'toggle_slug_video_callback',
			'videofly_general',
			'general_settings_section',
			array(
				esc_html__( 'Slug for video custom post', 'videofly' )
			)
		);

		add_settings_field(
			'slug_video_taxonomy',
			esc_html__( 'Change archive video slug', 'videofly' ),
			'toggle_slug_video_taxonomy_callback',
			'videofly_general',
			'general_settings_section',
			array(
				esc_html__( 'Slug for video texonomy', 'videofly' )
			)
		);

		add_settings_field(
			'slug_portfolio',
			esc_html__( 'Change custom post portfolio slug', 'videofly' ),
			'toggle_slug_portfolio_callback',
			'videofly_general',
			'general_settings_section',
			array(
				esc_html__( 'Slug for portfolio custom post', 'videofly' )
			)
		);

		add_settings_field(
			'slug_portfolio_taxonomy',
			esc_html__( 'Change archive portfolio slug', 'videofly' ),
			'toggle_slug_portfolio_taxonomy_callback',
			'videofly_general',
			'general_settings_section',
			array(
				esc_html__( 'Slug for portfolio taxonomy', 'videofly' )
			)
		);

		add_settings_field(
			'slug_event',
			esc_html__( 'Change custom post event slug', 'videofly' ),
			'toggle_slug_event_callback',
			'videofly_general',
			'general_settings_section',
			array(
				esc_html__( 'Slug for event custom post', 'videofly' )
			)
		);

		add_settings_field(
			'slug_event_taxonomy',
			esc_html__( 'Change archive event slug', 'videofly' ),
			'toggle_slug_event_taxonomy_callback',
			'videofly_general',
			'general_settings_section',
			array(
				esc_html__( 'Slug for event taxonomy', 'videofly' )
			)
		);

		add_settings_field(
			'slug_gallery',
			esc_html__( 'Change custom post gallery slug', 'videofly' ),
			'toggle_slug_gallery_callback',
			'videofly_general',
			'general_settings_section',
			array(
				esc_html__( 'Slug for gallery custom post', 'videofly' )
			)
		);

		add_settings_field(
			'slug_gallery_taxonomy',
			esc_html__( 'Change archive gallery slug', 'videofly' ),
			'toggle_slug_gallery_taxonomy_callback',
			'videofly_general',
			'general_settings_section',
			array(
				esc_html__( 'Slug for gallery taxonomy', 'videofly' )
			)
		);
	}

	add_settings_section(
		'general_settings_section',
		esc_html__( 'Support', 'videofly' ),
		'videofly_support_callback',
		'videofly_support'
	);

	register_setting( 'videofly_general', 'videofly_general');

} // END videofly_initialize_general_options

add_action( 'admin_init', 'videofly_initialize_general_options' );

/**************************************************
 * Section Callbacks
 *************************************************/

function videofly_general_options_callback()
{
	echo '<p>'.esc_html__( 'Below are the general options that this theme offers. You can enable/disable options and sections of your website.', 'videofly' ).'</p>';
} // END videofly_general_options_callback

function videofly_support_callback()
{
	echo '<strong>Dear customers</strong>,<br><br>

			<p>To make sure you recieve fast and quality support - please make sure you use our help desk for any questions regarding theme theme settings and questions about how it works. <strong style="color: red;">We offer support exclusively on the help desk</strong>, and emailing or contacting us via any other forms will result in lost time and longer waits. We try to respond to your questions as soon as possible, but please, be patient, calm and friendly. It it a huge amount of work to respond to everyone and being rude to our support guys will just not make it easier for any of us.</p>

			<p>If you found any bugs and issues while using our theme, please report them to our support guys.</p>

			<p>Note: Before submitting a ticket, try to disable ALL your plugins and make sure you are using the latest version of the theme. If you are not sure which is the latest one, you can check it on our website - www.touchsize.com. We cannot guarantee that the theme will work perfectly with all the plugins out there, since there might be conflicts or errors in your plugins. If you do find such conflicts, also - report them to our support team.</p>

			<p>Thank you very much for your understanding and we hope you are having a nice experience with the theme.</p>

			<a href="http://support.touchsize.com" target="_blank" class="go-help-desk">Go to help desk</a>';
}

function toggle_enable_preloader_callback($args)
{
	$options = get_option('videofly_general');

	$html =
		'<select name="videofly_general[enable_preloader]">
			<option value="Y" '. selected( @$options["enable_preloader"], 'Y', false ). '>' . esc_html__('Yes', 'videofly') . '</option>
			<option value="N" '. selected( @$options["enable_preloader"], 'N', false ). '>' . esc_html__('No', 'videofly') . '</option>
			<option value="FP" '. selected( @$options["enable_preloader"], 'FP', false ). '>' . esc_html__('Only on first page', 'videofly') . '</option>
		</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_onepage_website_callback($args)
{
	$options = get_option('videofly_general');

	$html =
		'<select name="videofly_general[onepage_website]">
			<option value="Y" '. selected( @$options["onepage_website"], 'Y', false ). '>' . esc_html__('Yes', 'videofly') . '</option>
			<option value="N" '. selected( @$options["onepage_website"], 'N', false ). '>' . esc_html__('No', 'videofly') . '</option>
		</select>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_featured_image_in_post_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<select name="videofly_general[featured_image_in_post]">
					<option value="Y" '. selected( @$options["featured_image_in_post"], 'Y', false ). '>'.esc_html__( 'Yes', 'videofly' ).'</option>
					<option value="N" '. selected( @$options["featured_image_in_post"], 'N', false ).'>'.esc_html__( 'No', 'videofly' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_enable_imagesloaded_callback($args){

	$options = get_option('videofly_general');

	$html = '<select name="videofly_general[enable_imagesloaded]">
					<option value="Y" '. selected( @$options["enable_imagesloaded"], 'Y', false ). '>'.esc_html__( 'Yes', 'videofly' ).'</option>
					<option value="N" '. selected( @$options["enable_imagesloaded"], 'N', false ).'>'.esc_html__( 'No', 'videofly' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_enable_lightbox_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<select name="videofly_general[enable_lightbox]">
					<option value="Y" '. selected( @$options["enable_lightbox"], 'Y', false ). '>'.esc_html__( 'Yes', 'videofly' ).'</option>
					<option value="N" '. selected( @$options["enable_lightbox"], 'N', false ).'>'.esc_html__( 'No', 'videofly' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_comment_system_callback($args)
{
	$options = get_option('videofly_general');

	$is_hidden = ( @$options["comment_system"] === 'default' ) ? 'hidden' : '';
	$facebook_id = @$options["facebook_id"];

	$html = '<select name="videofly_general[comment_system]" id="vdf_comment_system">
				<option value="default" '. selected( @$options["comment_system"], 'default', false ).'>'.esc_html__( 'Default', 'videofly' ).'</option>
				<option value="facebook" '. selected( @$options["comment_system"], 'facebook', false ).'>Facebook</option>
			</select>

			<p class="description">' .$args[0]. '</p>

			<div id="facebook_app_id" class="' . $is_hidden . '">
				<p>' . esc_html__('Get a Facebook App ID', 'videofly') . '</p>
				<input type="text" name="videofly_general[facebook_id]" value="' . esc_attr($facebook_id) . '" />
			</div>

			<script>
				jQuery( document ).ready(function( $ ) {
					var facebook_id = $("#facebook_app_id");

					$("#vdf_comment_system").change(function(){
						if ($(this).val() === "default") {
							facebook_id.addClass("hidden");
						} else {
							facebook_id.removeClass("hidden");
						}
					});
				});
			</script>';

	echo vdf_var_sanitize($html);
}

function facebook_page_modal_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<select name="videofly_general[enable_facebook_box]" id="enable_facebook_box">
				<option value="Y" '. selected( @$options["enable_facebook_box"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["enable_facebook_box"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	$enable_facebook_box_options = ($options["enable_facebook_box"] === 'Y') ? '' : 'hidden';

	$html .= '<div id="facebook_page" class="'.$enable_facebook_box_options .'">
				<p>' . esc_html__('Page name', 'videofly') . ':</p>
				<input type="text" id="facebook_box" name="videofly_general[facebook_name]" value="' . @$options['facebook_name'] . '" />
			 </div>';

	echo vdf_var_sanitize($html);
}

function toggle_show_wp_admin_bar_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<select name="videofly_general[show_wp_admin_bar]">
					<option value="Y" '. selected( @$options["show_wp_admin_bar"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
					<option value="N" '. selected( @$options["show_wp_admin_bar"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_enable_sticky_menu_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<select name="videofly_general[enable_sticky_menu]" id="enable_sticky_menu">
				<option value="Y" '. selected( @$options["enable_sticky_menu"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["enable_sticky_menu"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	$enable_sticky_menu_options = ($options["enable_sticky_menu"] === 'Y') ? '' : 'hidden';

	$html .= '<div id="sticky_menu_options" class="'.$enable_sticky_menu_options .'">
				<p>' . esc_html__('Background color', 'videofly') . ':</p>
				<input type="text" id="sticky_menu_bg_color" name="videofly_general[sticky_menu_bg_color]" value="' . @$options['sticky_menu_bg_color'] . '" />
				<div id="sticky_menu_bg_color_picker"></div>

				<p>' . esc_html__('Text color', 'videofly') . ':</p>
				<input type="text" id="sticky_menu_text_color" name="videofly_general[sticky_menu_text_color]" value="' . @$options['sticky_menu_text_color'] . '" />
				<div id="sticky_menu_text_color_picker"></div>

				<p>' . esc_html__('Show description', 'videofly') . ':</p>
				<select name="videofly_general[show_description]">
					<option ' . selected($options['show_description'], 'y', false) . ' value="y">' . esc_html__('Yes', 'videofly') . '</option>
					<option ' . selected($options['show_description'], 'n', false) . ' value="n">' . esc_html__('No', 'videofly') . '</option>
				</select>

				<p>' . esc_html__('Show icons', 'videofly') . ':</p>
				<select name="videofly_general[show_icons]">
					<option ' . selected($options['show_icons'], 'y', false) . ' value="y">' . esc_html__('Yes', 'videofly') . '</option>
					<option ' . selected($options['show_icons'], 'n', false) . ' value="n">' . esc_html__('No', 'videofly') . '</option>
				</select>
			</div>';

	echo vdf_var_sanitize($html);
}

function toggle_enable_mega_menu_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<select name="videofly_general[enable_mega_menu]" id="enable_mega_menu">
				<option value="Y" '. selected( @$options["enable_mega_menu"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["enable_mega_menu"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_like_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<select name="videofly_general[like]" class="enable-likes">
				<option value="y" '. selected( @$options["like"], 'y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="n" '. selected( @$options["like"], 'n', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_like_icon_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<div class="icons-likes">
				<ul class="imageRadioMetaUl perRow-3 builder-icon-list ts-custom-selector" data-selector="#likes-icons">
	               <li><i class="icon-heart clickable-element" data-option="heart"></i></li>
	               <li><i class="icon-tick clickable-element" data-option="tick"></i></li>
	               <li><i class="icon-thumb clickable-element" data-option="thumb"></i></li>
	            </ul>
	            <select class="hidden" name="videofly_general[like_icon]" id="likes-icons">
	                <option value="heart" '. selected( @$options["like_icon"], 'heart', false ). '>' . esc_html__( 'Heart', 'videofly' ) . '</option>
	                <option value="tick" '. selected( @$options["like_icon"], 'tick', false ). '>' . esc_html__( 'Tick', 'videofly' ) . '</option>
	                <option value="thumb" '. selected( @$options["like_icon"], 'thumb', false ). '>' . esc_html__( 'Star', 'videofly' ) . '</option>
	            </select>
	         </div>';
	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_dislike_icon_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<div class="icons-likes">
				<ul class="imageRadioMetaUl perRow-3 builder-icon-list ts-custom-selector" data-selector="#dislikes-icons">
	               <li><i class="icon-heart-broken clickable-element" data-option="heart-broken"></i></li>
	               <li><i class="icon-cancel clickable-element" data-option="cancel"></i></li>
	               <li><i class="icon-thumbs-down clickable-element" data-option="thumbs-down"></i></li>
	            </ul>
	            <select class="hidden" name="videofly_general[dislike_icon]" id="dislikes-icons">
	                <option value="heart-broken" '. selected( @$options["dislike_icon"], 'heart-broken', false ). '>' . esc_html__( 'Heart broken', 'videofly' ) . '</option>
	                <option value="cancel" '. selected( @$options["dislike_icon"], 'cancel', false ). '>' . esc_html__( 'Cancel', 'videofly' ) . '</option>
	                <option value="thumbs-down" '. selected( @$options["dislike_icon"], 'thumbs-down', false ). '>' . esc_html__( 'Thumbs Down', 'videofly' ) . '</option>
	            </select>
	         </div>';
	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_text_like_callback($args)
{
	$options = get_option('videofly_general');
	$text = isset($options['text_like']) ? $options['text_like'] : '';

	$html = '<div class="icons-likes">
				<input type="text" value="' . $text . '" name="videofly_general[text_like]"/>
			</div>';
	$html .= '<p class="description">'. $args[0] .'</p>';

	echo vdf_var_sanitize($html);
}

function toggle_text_dislike_callback($args)
{
	$options = get_option('videofly_general');
	$text = isset($options['text_dislike']) ? $options['text_dislike'] : '';

	$html = '<div class="icons-likes">
				<input type="text" value="' . $text . '" name="videofly_general[text_dislike]"/>
			</div>';
	$html .= '<p class="description">'. $args[0] .'</p>';

	echo vdf_var_sanitize($html);
}

function toggle_generate_likes_callback($args)
{
	$html = '<div class="generate-likes">
				<input type="button" id="generate-likes" value="' . esc_html__( 'Generate likes','videofly' ) . '" />
				<div style="display:none;" class="ts-wait">' . esc_html__('Please wait...', 'videofly') . '</div>
				<div style="display:none;" class="ts-succes-like">' . esc_html__('Done!', 'videofly') . '</div>
				<div style="display:none;" class="ts-error-like">' . esc_html__('Error!', 'videofly') . '</div>
			</div>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_custom_javascript_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<textarea name="videofly_general[custom_javascript]">'.esc_attr(@$options["custom_javascript"]).'</textarea>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_grid_excerpt_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<input type="text" name="videofly_general[grid_excerpt]" value="'.esc_attr(@$options["grid_excerpt"]).'" />';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_list_excerpt_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<input type="text" name="videofly_general[list_excerpt]" value="'.esc_attr(@$options["list_excerpt"]).'" />';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_bigpost_excerpt_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<input type="text" name="videofly_general[bigpost_excerpt]" value="'.esc_attr(@$options["bigpost_excerpt"]).'" />';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_timeline_excerpt_callback($args)
{
	$options = get_option('videofly_general');

	$html = '<input type="text" name="videofly_general[timeline_excerpt]" value="'.esc_attr(@$options["timeline_excerpt"]).'" />';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_featured_area_excerpt_callback($args)
{
	$options = get_option('videofly_general');
	$featuredAreaExcerpt = (isset($options['featured_area_excerpt']) && is_numeric($options['featured_area_excerpt'])) ? absint($options['featured_area_excerpt']) : 160;

	$html = '<input type="text" name="videofly_general[featured_area_excerpt]" value="' . $featuredAreaExcerpt . '" />';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_right_click_callback($args)
{
	$options = get_option('videofly_general');
	$rightClick = (isset($options['right_click']) && ($options['right_click'] == 'y' || $options['right_click'] == 'n')) ? $options['right_click'] : 'n';

	$html = '<select name="videofly_general[right_click]">
				<option ' . selected($rightClick, 'y', false) . ' value="y">' . esc_html__('Yes', 'videofly') . '</option>
				<option ' . selected($rightClick, 'n', false) . ' value="n">' . esc_html__('No', 'videofly') . '</option>
			</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_post_status_user_callback($args)
{
	$options = get_option('videofly_general');
	$status = isset($options['post_status_user']) ? $options['post_status_user'] : 'pending';

	$html = '<div>
	            <select name="videofly_general[post_status_user]">
	                <option value="publish" '. selected($status, 'publish', false) .'>'. esc_html__( 'Publish', 'videofly' ) .'</option>
	                <option value="pending" '. selected($status, 'pending', false ) .'>'. esc_html__( 'Pending', 'videofly' ) .'</option>
	            </select>
	         </div>';
	$html .= '<p class="description">' . $args[0] . '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_user_tab_post_callback($args)
{
	$options = get_option('videofly_general');
	$userTab = isset($options['user_tab_post']) && is_array($options['user_tab_post']) ? $options['user_tab_post'] : array('url' => '', 'upload' => '', 'embed' => '');
	$extern = isset($userTab['url']) ? 'checked' : '';
	$your = isset($userTab['upload']) ? 'checked' : '';
	$embed = isset($userTab['embed']) ? 'checked' : '';

	$html = '<input type="checkbox" name="videofly_general[user_tab_post][url]" '. $extern .'> '. esc_html__('External link', 'videofly') .'<br>'.
			'<input type="checkbox" name="videofly_general[user_tab_post][upload]" '. $your .'> '. esc_html__("MP4 video uploads", 'videofly') .'<br>'.
			'<input type="checkbox" name="videofly_general[user_tab_post][embed]" '. $embed .'> '. esc_html__("Video embeds", 'videofly') .'<br>';
	$html .= '<p class="description">'. @$args[0] .'</p>';

	echo vdf_var_sanitize($html);

}

function toggle_slug_video_callback($args)
{
	$options = get_option('videofly_general');

    $html = '<input type="text" name="videofly_general[slug_video]" value="' . $options['slug_video'] . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_slug_video_taxonomy_callback($args)
{
	$options = get_option('videofly_general');
	$slug = (isset($options['slug_video_taxonomy']) && $options['slug_video_taxonomy'] !== '') ? $options['slug_video_taxonomy'] : 'videos_categories';

    $html = '<input type="text" name="videofly_general[slug_video_taxonomy]" value="' . $slug . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_slug_portfolio_callback($args)
{
	$options = get_option('videofly_general');
	$slug = (isset($options['slug_portfolio']) && $options['slug_portfolio'] !== '') ? $options['slug_portfolio'] : 'portfolio';

    $html = '<input type="text" name="videofly_general[slug_portfolio]" value="' . $slug . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_slug_portfolio_taxonomy_callback($args)
{
	$options = get_option('videofly_general');
	$slug = (isset($options['slug_portfolio_taxonomy']) && $options['slug_portfolio_taxonomy'] !== '') ? $options['slug_portfolio_taxonomy'] : 'portfolio-categories';

    $html = '<input type="text" name="videofly_general[slug_portfolio_taxonomy]" value="' . $slug . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_slug_event_callback($args)
{
	$options = get_option('videofly_general');
	$slug = (isset($options['slug_event']) && $options['slug_event'] !== '') ? $options['slug_event'] : 'event';

    $html = '<input type="text" name="videofly_general[slug_event]" value="' . $slug . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_slug_event_taxonomy_callback($args)
{
	$options = get_option('videofly_general');
	$slug = (isset($options['slug_event_taxonomy']) && $options['slug_event_taxonomy'] !== '') ? $options['slug_event_taxonomy'] : 'event-categories';

    $html = '<input type="text" name="videofly_general[slug_event_taxonomy]" value="' . $slug . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_slug_gallery_callback($args)
{
	$options = get_option('videofly_general');
	$slug = (isset($options['slug_gallery']) && $options['slug_gallery'] !== '') ? $options['slug_gallery'] : 'gallery';

    $html = '<input type="text" name="videofly_general[slug_gallery]" value="' . $slug . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_slug_gallery_taxonomy_callback($args)
{
	$options = get_option('videofly_general');
	$slug = (isset($options['slug_gallery_taxonomy']) && $options['slug_gallery_taxonomy'] !== '') ? $options['slug_gallery_taxonomy'] : 'gallery-categories';

    $html = '<input type="text" name="videofly_general[slug_gallery_taxonomy]" value="' . $slug . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function videofly_initialize_image_sizes_options($args) {

	if( false === get_option( 'videofly_image_sizes' ) ) {

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
			'product' => array(
				'width'  => '350',
				'height' => '450',
				'mode'   => 'crop'
			),
			'featured_article' => array(
				'width'  => '720',
				'height' => '300',
				'mode'   => 'resize'
			),
			'gallery-masonry-layout' => array('height-mobile' => 300, 'height-desktop' => 500)
		);

		add_option( 'videofly_image_sizes', $defaults );
	}

	// Register  section
	add_settings_section(
		'image_sizes_section',
		esc_html__( 'Image sizes', 'videofly' ),
		'videofly_image_sizes_callback',
		'videofly_image_sizes'
	);

	add_settings_field(
		'grid',
		esc_html__( 'Grid view', 'videofly' ),
		'toggle_image_sizes_grid_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'thumbnails',
		esc_html__( 'Thumbnails view', 'videofly' ),
		'toggle_image_sizes_thumbnails_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'bigpost',
		esc_html__( 'Big post view', 'videofly' ),
		'toggle_image_sizes_bigpost_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'superpost',
		esc_html__( 'Super post view', 'videofly' ),
		'toggle_image_sizes_superpost_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'single',
		esc_html__( 'Single view', 'videofly' ),
		'toggle_image_sizes_single_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'portfolio',
		esc_html__( 'Portfolio view', 'videofly' ),
		'toggle_image_sizes_portfolio_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'portfolio',
		esc_html__( 'Featured area view', 'videofly' ),
		'toggle_image_sizes_featarea_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'slider',
		esc_html__( 'Slider image size', 'videofly' ),
		'toggle_image_sizes_slider_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'carousel',
		esc_html__( 'Carousel image size', 'videofly' ),
		'toggle_image_sizes_carousel_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'timeline',
		esc_html__( 'Timeline post view', 'videofly' ),
		'toggle_image_sizes_timeline_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'product',
		esc_html__( 'Product article', 'videofly' ),
		'toggle_image_sizes_product_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'gallery-masonry-layout',
		esc_html__( 'Masonry layout gallery', 'videofly' ),
		'toggle_image_sizes_gallery_masonry_layout_callback',
		'videofly_image_sizes',
		'image_sizes_section',
		array(
			esc_html__( '', 'videofly' )
		)
	);
	add_settings_field(
		'featured_article',
		esc_html__( 'Featured article', 'videofly' ),
		'toggle_image_sizes_featured_article_callback',
		'videofly_image_sizes',
		'image_sizes_section'
	);

	register_setting( 'videofly_image_sizes', 'videofly_image_sizes');
}

add_action( 'admin_init', 'videofly_initialize_image_sizes_options' );


function videofly_image_sizes_callback() {
	echo '<p>'.esc_html__( 'In this tab you can choose the dimensions for the images that are used on your website. Caution - these are not the sizes that will be shown on the website as the website is adaptive, but it is the size of the images that will be used. We strongly recommend to use given settings and not to fiddle with any as long as you are not sure what you are doing.', 'videofly' ).'</p>';
}

function toggle_image_sizes_grid_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['grid'];

	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[grid][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[grid][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videofly_image_sizes[grid][mode]" id="img-sizes-grid-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-grid-crop">'.esc_html__('Crop', 'videofly').' </label><br/>
				<input type="radio" name="videofly_image_sizes[grid][mode]" id="img-sizes-grid-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-grid-resize">'.esc_html__('Resize', 'videofly').'</label>
			</p>';

	echo vdf_var_sanitize($html);
}

function toggle_image_sizes_thumbnails_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['thumbnails'];


	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[thumbnails][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[thumbnails][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<input type="hidden" name="videofly_image_sizes[thumbnails][mode]" value="crop"><br/><br/>
			<em>'.esc_html__('Images for thumbnails view are cropped', 'videofly').'</em>';

	echo vdf_var_sanitize($html);
}

function toggle_image_sizes_bigpost_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['bigpost'];

	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[bigpost][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[bigpost][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videofly_image_sizes[bigpost][mode]" id="img-sizes-bigpost-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-bigpost-crop">'.esc_html__('Crop', 'videofly').' </label><br/>
				<input type="radio" name="videofly_image_sizes[bigpost][mode]" id="img-sizes-bigpost-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-bigpost-resize">'.esc_html__('Resize', 'videofly').'</label>
			</p>';

	echo vdf_var_sanitize($html);
}

function toggle_image_sizes_superpost_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['superpost'];

	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[superpost][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[superpost][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videofly_image_sizes[superpost][mode]" id="img-sizes-superpost-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-superpost-crop">'.esc_html__('Crop', 'videofly').' </label><br/>
				<input type="radio" name="videofly_image_sizes[superpost][mode]" id="img-sizes-superpost-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-superpost-resize">'.esc_html__('Resize', 'videofly').'</label>
			</p>';

	echo vdf_var_sanitize($html);
}

function toggle_image_sizes_single_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['single'];

	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[single][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[single][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videofly_image_sizes[single][mode]" id="img-sizes-single-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-single-crop">'.esc_html__('Crop', 'videofly').' </label><br/>
				<input type="radio" name="videofly_image_sizes[single][mode]" id="img-sizes-single-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-single-resize">'.esc_html__('Resize', 'videofly').'</label>
			</p>';

	echo vdf_var_sanitize($html);
}

function toggle_image_sizes_timeline_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['timeline'];

	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[timeline][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[timeline][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videofly_image_sizes[timeline][mode]" id="img-sizes-timeline-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-timeline-crop">'.esc_html__('Crop', 'videofly').' </label><br/>
				<input type="radio" name="videofly_image_sizes[timeline][mode]" id="img-sizes-timeline-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-timeline-resize">'.esc_html__('Resize', 'videofly').'</label>
			</p>';

	echo vdf_var_sanitize($html);
}

function toggle_image_sizes_product_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['product'];

	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[product][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[product][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videofly_image_sizes[product][mode]" id="img-sizes-product-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-product-crop">'.esc_html__('Crop', 'videofly').' </label><br/>
				<input type="radio" name="videofly_image_sizes[product][mode]" id="img-sizes-product-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-product-resize">'.esc_html__('Resize', 'videofly').'</label>
			</p>';

	echo vdf_var_sanitize($html);
}

function toggle_image_sizes_portfolio_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['portfolio'];

	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[portfolio][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[portfolio][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videofly_image_sizes[portfolio][mode]" id="img-sizes-portfolio-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-portfolio-crop">'.esc_html__('Crop', 'videofly').' </label><br/>
				<input type="radio" name="videofly_image_sizes[portfolio][mode]" id="img-sizes-portfolio-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-portfolio-resize">'.esc_html__('Resize', 'videofly').'</label>
			</p>';

	echo vdf_var_sanitize($html);
}

function toggle_image_sizes_featarea_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['featarea'];

	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[featarea][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[featarea][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videofly_image_sizes[featarea][mode]" id="img-sizes-featarea-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-featarea-crop">'.esc_html__('Crop', 'videofly').' </label><br/>
				<input type="radio" name="videofly_image_sizes[featarea][mode]" id="img-sizes-featarea-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-featarea-resize">'.esc_html__('Resize', 'videofly').'</label>
			</p>';

	echo vdf_var_sanitize($html);
}

function toggle_image_sizes_slider_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['slider'];

	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[slider][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[slider][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videofly_image_sizes[slider][mode]" id="img-sizes-slider-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-slider-crop">'.esc_html__('Crop', 'videofly').' </label><br/>
				<input type="radio" name="videofly_image_sizes[slider][mode]" id="img-sizes-slider-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-slider-resize">'.esc_html__('Resize', 'videofly').'</label>
			</p>';

	echo vdf_var_sanitize($html);
}

function toggle_image_sizes_carousel_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['carousel'];

	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[carousel][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[carousel][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videofly_image_sizes[carousel][mode]" id="img-sizes-carousel-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-carousel-crop">'.esc_html__('Crop', 'videofly').' </label><br/>
				<input type="radio" name="videofly_image_sizes[carousel][mode]" id="img-sizes-carousel-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-carousel-resize">'.esc_html__('Resize', 'videofly').'</label>
			</p>';

	echo vdf_var_sanitize($html);
}

function toggle_image_sizes_gallery_masonry_layout_callback($args)
{
	$options = get_option('videofly_image_sizes');
	$heightDesktop = (isset($options['gallery-masonry-layout']['height-desktop']) && is_numeric($options['gallery-masonry-layout']['height-desktop'])) ? $options['gallery-masonry-layout']['height-desktop'] : 500;
	$heightMobile = (isset($options['gallery-masonry-layout']['height-mobile']) && is_numeric($options['gallery-masonry-layout']['height-mobile'])) ? $options['gallery-masonry-layout']['height-mobile'] : 300;

	$html = '<p>' . esc_html__('Change image width for desktop (max-width)', 'videofly') . '</p>';
	$html .= '<input type="text" name="videofly_image_sizes[gallery-masonry-layout][height-desktop]" value="' . $heightDesktop . '" readonly  id="ts-masonry-layout-height-desktop"/>
			<div id="ts-slider-masonry-layout-height-desktop"></div>';

	$html .= '<p>' . esc_html__('Change image width for mobile (max-width)', 'videofly') . '</p>';
	$html .= '<input type="text" name="videofly_image_sizes[gallery-masonry-layout][height-mobile]" value="' . $heightMobile . '" readonly  id="ts-masonry-layout-height-mobile"/>
			<div id="ts-slider-masonry-layout-height-mobile"></div>';
	$html .= 	'<script>
					jQuery(document).ready(function(){
						ts_slider_pips(250, 600, 10, ' . $heightDesktop . ', "ts-slider-masonry-layout-height-desktop", "ts-masonry-layout-height-desktop");
						ts_slider_pips(150, 400, 10, ' . $heightMobile . ', "ts-slider-masonry-layout-height-mobile", "ts-masonry-layout-height-mobile");
					});
				</script>';

	$html .= '<p class="description">' . @$args[0] . '</p>';

	echo vdf_var_sanitize($html);

}

function toggle_image_sizes_featured_article_callback($args) {

	$options = get_option( 'videofly_image_sizes' );
	$options = @$options['featured_article'];

	$html = '<p>'.esc_html__('Width', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[featured_article][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.esc_html__('Height', 'videofly').' (px)</p>
			<input type="text" name="videofly_image_sizes[featured_article][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videofly_image_sizes[featured_article][mode]" id="img-sizes-featured_article-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-featured_article-crop">'.esc_html__('Crop', 'videofly').' </label><br/>
				<input type="radio" name="videofly_image_sizes[featured_article][mode]" id="img-sizes-featured_article-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-featured_article-resize">'.esc_html__('Resize', 'videofly').'</label>
			</p>';

	echo vdf_var_sanitize($html);
}

function videofly_initialize_layout_options() {

	if( false === get_option( 'videofly_layout' ) ) {

		add_option( 'videofly_layout', array() );

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
			'show_layout',
			'product_layout'
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
				'display-title'   => 'title-below-image',
				'show-meta'       => 'y',
				'elements-per-row'=> '3',
				'special-effects' => 'none'
			),
			'list' => array(
				'display-title'   => 'title-below-image',
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
				'display-title'   => 'title-below-image',
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
			if ($layout_id === 'single_layout' || $layout_id === 'page_layout' || $layout_id == 'product_layout') {
				$data[$layout] = $default_style['sidebar'];
			}

			$data[$layout] = $default_style;
		}

		update_option( 'videofly_layout', $data );

	} // end if

	// Register  section
	add_settings_section(
		'layout_settings_section',
		esc_html__( 'Default layout settings', 'videofly' ),
		'videofly_layout_category_callback',
		'videofly_layout'
	);

	add_settings_field(
		'single_layout',
		esc_html__( 'Single', 'videofly' ),
		'toggle_single_layout_callback',
		'videofly_layout',
		'layout_settings_section'
	);

	add_settings_field(
		'page_layout',
		esc_html__( 'Page', 'videofly' ),
		'toggle_page_layout_callback',
		'videofly_layout',
		'layout_settings_section'
	);

	add_settings_field(
		'blog_layout',
		esc_html__( 'Blog page', 'videofly' ),
		'toggle_blog_layout_callback',
		'videofly_layout',
		'layout_settings_section'
	);

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if( is_plugin_active( 'woocommerce/woocommerce.php' ) ){

      	add_settings_field(
			'product_layout',
			esc_html__( 'Product', 'videofly' ),
			'toggle_product_layout_callback',
			'videofly_layout',
			'layout_settings_section'
		);

      	add_settings_field(
			'shop_layout',
			esc_html__( 'Shop page', 'videofly' ),
			'toggle_shop_layout_callback',
			'videofly_layout',
			'layout_settings_section'
		);
	}

	add_settings_field(
		'category_layout',
		esc_html__( 'Category', 'videofly' ),
		'toggle_category_layout_callback',
		'videofly_layout',
		'layout_settings_section'
	);
	add_settings_field(
		'author_layout',
		esc_html__( 'Author', 'videofly' ),
		'toggle_author_layout_callback',
		'videofly_layout',
		'layout_settings_section'
	);

	add_settings_field(
		'search_layout',
		esc_html__( 'Search', 'videofly' ),
		'toggle_search_layout_callback',
		'videofly_layout',
		'layout_settings_section'
	);

	add_settings_field(
		'archive_layout',
		esc_html__( 'Archive', 'videofly' ),
		'toggle_archive_layout_callback',
		'videofly_layout',
		'layout_settings_section'
	);

	add_settings_field(
		'tags_layout',
		esc_html__( 'Tags', 'videofly' ),
		'toggle_tags_layout_callback',
		'videofly_layout',
		'layout_settings_section'
	);

	register_setting( 'videofly_layout', 'videofly_layout');
}

add_action( 'admin_init', 'videofly_initialize_layout_options' );

function videofly_layout_category_callback() {
	echo '<p>'.esc_html__( 'This is the default layouts settings area. Here you can set the defaults for your website. Default sidebar settings and the way articles are going to be shown on archive pages.', 'videofly' ).'</p>';
}

function toggle_single_layout_callback($args)
{
	$options = get_option('videofly_layout');

	$html = '<table><tr><td><strong>' . esc_html__( 'Sidebar position', 'videofly' ) . '</strong>';
	$html.= videofly_sidebar_settings_generator('single_layout', $options);
	$html.= '</td></tr></table>';

	echo vdf_var_sanitize($html);
}

function toggle_product_layout_callback($args)
{
	$options = get_option('videofly_layout');

	$html = '<table><tr><td><strong>' . esc_html__( 'Sidebar position', 'videofly' ) . '</strong>';
	$html.= videofly_sidebar_settings_generator('product_layout', $options);
	$html.= '</td></tr></table>';

	echo vdf_var_sanitize($html);
}

function toggle_shop_layout_callback($args)
{
	$options = get_option('videofly_layout');

	$html = '<table><tr><td><strong>' . esc_html__( 'Sidebar position', 'videofly' ) . '</strong>';
	$html.= videofly_sidebar_settings_generator('shop_layout', $options);
	$html.= '</td></tr></table>';

	echo vdf_var_sanitize($html);
}

function toggle_page_layout_callback($args)
{
	$options = get_option('videofly_layout');

	$html = '<table><tr><td><strong>' . esc_html__( 'Sidebar position', 'videofly' ) . '</strong>';
	$html.= videofly_sidebar_settings_generator('page_layout', $options);
	$html.= '</td></tr></table>';

	echo vdf_var_sanitize($html);
}

function toggle_category_layout_callback($args)
{
	$options = get_option('videofly_layout');

	$html = '<table><tr><td><strong>' . esc_html__( 'Sidebar position', 'videofly' ) . '</strong>';
	$html.= videofly_sidebar_settings_generator('category_layout', $options);
	$html.= '</td></tr><tr><td>'.videofly_layout_style_generator('category_layout', $options).'</td></tr></table>';

	echo vdf_var_sanitize($html);
}

function toggle_blog_layout_callback($args)
{
	$options = get_option('videofly_layout');

	$html = '<table><tr><td><strong>' . esc_html__( 'Sidebar position', 'videofly' ) . '</strong>';
	$html.= videofly_sidebar_settings_generator('blog_layout', $options);
	$html.= '</td></tr><tr><td>'.videofly_layout_style_generator('blog_layout', $options).'</td></tr></table>';

	echo vdf_var_sanitize($html);
}

function toggle_author_layout_callback($args)
{
	$options = get_option('videofly_layout');

	$html = '<table><tr><td><strong>' . esc_html__( 'Sidebar position', 'videofly' ) . '</strong>';
	$html.= videofly_sidebar_settings_generator('author_layout', $options);
	$html.= '</td></tr><tr><td>'.videofly_layout_style_generator('author_layout', $options).'</td></tr></table>';

	echo vdf_var_sanitize($html);
}

function toggle_search_layout_callback($args)
{
	$options = get_option('videofly_layout');

	$html = '<table><tr><td><strong>' . esc_html__( 'Sidebar position', 'videofly' ) . '</strong>';
	$html.= videofly_sidebar_settings_generator('search_layout', $options);
	$html.= '</td></tr><tr><td>'.videofly_layout_style_generator('search_layout', $options).'</td></tr></table>';

	echo vdf_var_sanitize($html);
}

function toggle_archive_layout_callback($args)
{
	$options = get_option('videofly_layout');

	$html = '<table><tr><td><strong>' . esc_html__( 'Sidebar position', 'videofly' ) . '</strong>';
	$html.= videofly_sidebar_settings_generator('archive_layout', $options);
	$html.= '</td></tr><tr><td>'.videofly_layout_style_generator('archive_layout', $options).'</td></tr></table>';

	echo vdf_var_sanitize($html);
}

function toggle_tags_layout_callback($args)
{
	$options = get_option('videofly_layout');

	$html = '<table><tr><td><strong>' . esc_html__( 'Sidebar position', 'videofly' ) . '</strong>';
	$html.= videofly_sidebar_settings_generator('tags_layout', $options);
	$html.= '</td></tr><tr><td>'.videofly_layout_style_generator('tags_layout', $options).'</td></tr></table>';

	echo vdf_var_sanitize($html);
}

function videofly_sidebar_settings_generator($section = 'category_layout', $options = array()) {

	$html = '<select name="videofly_layout['.$section.'][sidebar][position]">
				<option value="none" '. selected( @$options[$section]['sidebar']['position'], 'none', 0 ).'>None</option>
				<option value="left" '. selected( @$options[$section]['sidebar']['position'], 'left', 0 ).'>Left</option>
				<option value="right" '. selected( @$options[$section]['sidebar']['position'], 'right', 0 ).'>Right</option>
			</select>';

	$html .= '<strong>' . esc_html__( 'Sidebar size', 'videofly' ).'</strong>';
	$html .= '<select name="videofly_layout['.$section.'][sidebar][size]">
				<option value="1-3" '. selected( @$options[$section]['sidebar']['size'], '1-3', 0 ).'>1/3</option>
				<option value="1-4" '. selected( @$options[$section]['sidebar']['size'], '1-4', 0 ).'>1/4</option>
			</select>';

	$html .= '<strong>' . esc_html__( 'Sidebar name', 'videofly' ).'</strong>';
	$html .= ts_sidebars_drop_down(@$options[$section]['sidebar']['id'], $section.'_sidebar', 'videofly_layout['.$section.'][sidebar][id]');

	return $html;
}

function videofly_layout_style_generator($section = 'category_layout', $options = array()) {

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
                            <label for="'.$prefix.'-last-posts-display-mode">'.esc_html__( 'How to display', 'videofly' ) . ':</label>
                        </td>
                        <td>
                            <select name="videofly_layout['.$section.'][display-mode]" class="'.$prefix.'-last-posts-display-mode">
                                <option value="grid" '. selected( @$options[$section]['display-mode'], 'grid', 0 ).'>'.esc_html__( 'Grid', 'videofly' ) .'</option>
                                <option value="list" ' . selected( @$options[$section]['display-mode'], 'list', 0 ).'>'.esc_html__( 'List', 'videofly' ) .'</option>
                                <option value="thumbnails" ' . selected( @$options[$section]['display-mode'], 'thumbnails', 0 ).'>'.esc_html__( 'Thumbnails', 'videofly' ) .'</option>
                                <option value="big-post" ' . selected( @$options[$section]['display-mode'], 'big-post', 0 ) . '>'.esc_html__( 'Big post', 'videofly' ) .'</option>
                                <option value="super-post" ' . selected( @$options[$section]['display-mode'], 'super-post', 0 ) . '>'.esc_html__( 'Super Post', 'videofly' ) .'</option>
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
                                    <label for="'.$section.'-last-posts-grid-title">'.esc_html__( 'Title', 'videofly' ).':</label>
                                </td>
                                <td>
                                    <select name="videofly_layout['.$section.'][grid][display-title]" id="'.$section.'-last-posts-grid-title">
                                        <option value="title-above-image" ' . selected( @$options[$section]['grid']['display-title'], 'title-above-image', 0 ) . '>'.esc_html__( 'Above image', 'videofly' ).'</option>
                                        <option value="title-below-image" ' . selected( @$options[$section]['grid']['display-title'], 'title-below-image', 0 ) . '>'.esc_html__( 'Above excerpt', 'videofly' ).'</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-grid-show-meta">'.esc_html__( 'Show meta', 'videofly' ).':</label>
                                </td>
                                <td>
                                    <input type="radio" name="videofly_layout['.$section.'][grid][show-meta]" id="'.$section.'-last-posts-grid-show-meta-y"  value="y" '.checked( @$options[$section]['grid']['show-meta'], 'y', 0 ).' />
                                    <label for="'.$section.'-last-posts-grid-show-meta-y">'.esc_html__( 'Yes', 'videofly' ).'</label>
                                    <input type="radio" name="videofly_layout['.$section.'][grid][show-meta]" id="'.$section.'-last-posts-grid-show-meta-n" value="n" '.checked( @$options[$section]['grid']['show-meta'], 'n', 0 ).'/>
                                    <label for="'.$section.'-last-posts-grid-show-meta-n">'.esc_html__( 'No', 'videofly' ).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-grid-el-per-row">'.esc_html__( 'Elements per row', 'videofly' ).'</label>
                                </td>
                                <td>
                                    <select name="videofly_layout['.$section.'][grid][elements-per-row]" id="'.$section.'-last-posts-grid-el-per-row">
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
                                    <label for="'.$section.'-last-posts-list-title">'.esc_html__( 'Title:', 'videofly' ).'</label>
                                </td>
                                <td>
                                    <select name="videofly_layout['.$section.'][list][display-title]" id="'.$section.'-last-posts-list-title">
                                        <option value="title-above-image" '. selected( @$options[$section]['list']['display-title'], 'title-above-image', 0 ) .'>'.esc_html__( 'Above image', 'videofly' ).'</option>
                                        <option value="title-below-image" '. selected( @$options[$section]['list']['display-title'], 'title-below-image', 0 ) .'>'.esc_html__( 'Above excerpt', 'videofly' ).'</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-list-show-meta">'.esc_html__( 'Show meta', 'videofly' ).':</label>
                                </td>
                                <td>
                                    <input type="radio" name="videofly_layout['.$section.'][list][show-meta]" id="'.$section.'-last-posts-list-show-meta-y"  value="y" '.checked( @$options[$section]['list']['show-meta'], 'y', 0 ).'  />
                                    <label for="'.$section.'-last-posts-list-show-meta-y">'.esc_html__( 'Yes', 'videofly' ).'</label>
                                    <input type="radio" name="videofly_layout['.$section.'][list][show-meta]" id="'.$section.'-last-posts-list-show-meta-n" value="n" '.checked( @$options[$section]['list']['show-meta'], 'n', 0 ).'/>
                                    <label for="'.$section.'-last-posts-list-show-meta-n">'.esc_html__( 'No', 'videofly' ).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.esc_html__( 'Content split', 'videofly' ).'</td>
                                <td>
                                    <select name="videofly_layout['.$section.'][list][image-split]" id="'.$section.'-last-posts-list-image-split">
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
                                    <label for="'.$section.'-last-posts-thumbnail-posts-per-row">'.esc_html__( 'Number of posts per row', 'videofly' ).':</label>
                                </td>
                                <td>
                                    <select name="videofly_layout['.$section.'][thumbnails][elements-per-row]" id="'.$section.'-last-posts-thumbnail-posts-per-row">
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
                                    <label for="'.$section.'-last-posts-big-post-title">'.esc_html__( 'Title', 'videofly' ).':</label>
                                </td>
                                <td>
                                    <select name="videofly_layout['.$section.'][big-post][display-title]" id="'.$section.'-last-posts-big-post-title">
                                        <option value="title-above-image" ' . selected( @$options[$section]['big-post']['display-title'], 'title-above-image', 0 ) .'>'.esc_html__( 'Above image', 'videofly' ).'</option>
                                        <option value="title-below-image" ' . selected( @$options[$section]['big-post']['display-title'], 'title-below-image', 0 ) .'>'.esc_html__( 'Above excerpt', 'videofly' ).'</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-big-post-show-meta">'.esc_html__( 'Show meta', 'videofly' ).':</label>
                                </td>
                                <td>
                                    <input type="radio" name="videofly_layout['.$section.'][big-post][show-meta]" id="'.$section.'-last-posts-big-post-show-meta-y"  value="y" '.checked( @$options[$section]['big-post']['show-meta'], 'y', 0 ).'   />
                                    <label for="'.$section.'-last-posts-big-post-show-meta-y">'.esc_html__( 'Yes', 'videofly' ).'</label>

                                    <input type="radio" name="videofly_layout['.$section.'][big-post][show-meta]" id="'.$section.'-last-posts-big-post-show-meta-n" value="n" '.checked( @$options[$section]['big-post']['show-meta'], 'n', 0 ).' />
                                    <label for="'.$section.'-last-posts-big-post-show-meta-n">'.esc_html__( 'No', 'videofly' ).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.esc_html__( 'Content split', 'videofly' ).'</td>
                                <td>
                                    <select name="videofly_layout['.$section.'][big-post][image-split]" id="'.$section.'-last-posts-big-post-image-split">
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
                                    <label for="'.$section.'-last-posts-super-post-posts-per-row">'.esc_html__( 'Number of posts per row', 'videofly' ).':</label>
                                </td>
                                <td>
                                    <select name="videofly_layout['.$section.'][super-post][elements-per-row]" id="'.$section.'-last-posts-super-post-posts-per-row">
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

function videofly_initialize_styles_options()
{
	// delete_option('videofly_styles');
	if( false === get_option( 'videofly_styles' ) ) {
		$defaultStyles = array(
			'boxed_layout' => 'N',
			'image_hover_effect' => 'Y',
			'theme_custom_bg' => 'color',
			'theme_bg_pattern' => '',
			'theme_bg_color' => '#FFFFFF',
			'bg_image' => '',
			'overlay_effect_for_images' => 'N',
			'overlay_effect_type' => 'dots',
			'sharing_overlay' => 'Y',
			'facebook_image' => '',
			'scroll_to_top' => 'y',
			'style_hover' => 'style2',
			'hover_gallery' => 'slide-from-bottom',
			'header_position' => 'top',
			'site_width' => '1240',
			'logo' => array(
				'type' => 'image',
				'image_url' => '',
				'font_name' => '0',
				'font_family' => '',
				'font_size'   => '32',
				'font_weight' => 'normal',
				'font_style' => 'normal',
				'text' => 'Videofly',
				'font_subsets' => array('latin'),
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'standard_font' => 'Roboto',
				'retina_logo' => 'Y',
				'retina_width' => '315',
				'retina_height' => '112',
			),
			'effect_in_general' => 'none',
			'effect_out_general' => 'none'
		);

		if( !function_exists( 'has_site_icon' ) ){
			$defaultStyles['favicon'] = '';
		}

		add_option( 'videofly_styles', $defaultStyles);

	} // end if

	// Register styles section
	add_settings_section(
		'style_settings_section',
		esc_html__( 'Styles Options', 'videofly' ),
		'videofly_styles_callback',
		'videofly_styles'
	);

	add_settings_field(
		'boxed_layout',
		esc_html__( 'Boxed Layout', 'videofly' ),
		'toggle_boxed_layout_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( 'If enabled it will add white background to content and limit it to the site that is set in general settings.', 'videofly' )
		)
	);

	add_settings_field(
		'theme_custom_bg',
		esc_html__( 'Theme background customization', 'videofly' ),
		'toggle_theme_custom_bg_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( 'Background options for your website. You can set image background, background color or pattern.', 'videofly' )
		)
	);
	if( !function_exists( 'has_site_icon' ) ){
		add_settings_field(
			'favicon',
			esc_html__( 'Custom favicon', 'videofly' ),
			'toggle_favicon_callback',
			'videofly_styles',
			'style_settings_section',
			array(
				'Upload your own favicon for your website.'
			)
		);
	}

	add_settings_field(
		'facebook_image',
		esc_html__( 'Facebook image', 'videofly' ),
		'toggle_facebook_image_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			'Upload your own facebook image for your website.'
		)
	);

	add_settings_field(
		'overlay_effect_for_images',
		esc_html__( 'Enable overlay stripes/dots effect for images', 'videofly' ),
		'toggle_overlay_effect_for_images_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( 'If enabled, it will add subtle effect over images in archive pages and single featured images.', 'videofly' )
		)
	);

	add_settings_field(
		'sharing_overlay',
		esc_html__( 'Enable sharing overlay buttons in views', 'videofly' ),
		'toggle_sharing_overlay_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( 'If enabled, it will show sharing buttons on mouse over in post views.', 'videofly' )
		)
	);

	add_settings_field(
		'logo_type',
		esc_html__( 'Logo type', 'videofly' ),
		'toggle_logo_type_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( 'Choose which type of logo do you want to use. If text, select the font and the styles you need. If you want to use custom image logo use the uploader provided.', 'videofly' )
		)
	);

	add_settings_field(
		'scroll_to_top',
		esc_html__( 'Enable scroll to top button', 'videofly' ),
		'toggle_scroll_to_top_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( 'Enable scroll to top button', 'videofly' )
		)
	);

	add_settings_field(
		'style_hover',
		esc_html__( 'Change style for hover', 'videofly' ),
		'toggle_style_hover_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( 'Change the hover styling', 'videofly' )
		)
	);

	add_settings_field(
		'hover_gallery',
		esc_html__( 'Change style for hover image gallery', 'videofly' ),
		'toggle_hover_gallery_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( 'Change the hover styling for gallery images', 'videofly' )
		)
	);

	add_settings_field(
		'header_position',
		esc_html__( 'Choose header position', 'videofly' ),
		'toggle_header_position_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( 'Left, top or right', 'videofly' )
		)
	);

	add_settings_field(
		'site_width',
		esc_html__( 'Site default width', 'videofly' ),
		'toggle_site_width_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( '', 'videofly' )
		)
	);

	add_settings_field(
		'effect_in_general',
		esc_html__( 'Page change effect in', 'videofly' ),
		'toggle_effect_in_general_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( '', 'videofly' )
		)
	);

	add_settings_field(
		'effect_out_general',
		esc_html__( 'Page change effect out', 'videofly' ),
		'toggle_effect_out_general_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( '', 'videofly' )
		)
	);

	add_settings_field(
		'bordered_widgets',
		esc_html__( 'Bordered widgets', 'videofly' ),
		'toggle_bordered_widgets_callback',
		'videofly_styles',
		'style_settings_section',
		array(
			esc_html__( '', 'videofly' )
		)
	);

	register_setting( 'videofly_styles', 'videofly_styles');

} // end videofly_initialize_theme_options


add_action( 'admin_init', 'videofly_initialize_styles_options' );

/**************************************************
 * Styles Section Callbacks
 *************************************************/

function videofly_styles_callback()
{
	echo '<p>'.esc_html__( 'Settings for your website styling. Here you can change colors, effects, logo type, custom favicon, background.', 'videofly' ).'</p>';
?>

<?php
} // end videofly_styles_callback

function toggle_boxed_layout_callback($args)
{
	$options = get_option('videofly_styles');

	$html = '<select name="videofly_styles[boxed_layout]">
					<option value="Y" '. selected( @$options["boxed_layout"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
					<option value="N" '. selected( @$options["boxed_layout"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_image_hover_effect_callback($args)
{
	$options = get_option('videofly_styles');

	$html = '<select name="videofly_styles[image_hover_effect]">
					<option value="Y" '. selected( @$options["image_hover_effect"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
					<option value="N" '. selected( @$options["image_hover_effect"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}


function toggle_sharing_overlay_callback($args)
{
	$options = get_option('videofly_styles');

	$html = '<select name="videofly_styles[sharing_overlay]">
					<option value="Y" '. selected( @$options["sharing_overlay"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
					<option value="N" '. selected( @$options["sharing_overlay"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}


function toggle_theme_custom_bg_callback($args)
{
	$options = get_option('videofly_styles');

	$html = '<select name="videofly_styles[theme_custom_bg]" id="custom-bg-options">
					<option value="pattern" '. selected( @$options["theme_custom_bg"], 'pattern', false ).'>'.esc_html__( 'Pattern', 'videofly' ).'</option>
					<option value="color" '. selected( @$options["theme_custom_bg"], 'color', false ).'>'.esc_html__( 'Color', 'videofly' ).'</option>
					<option value="image" '. selected( @$options["theme_custom_bg"], 'image', false ).'>'.esc_html__( 'Image', 'videofly' ).'</option>
					<option value="N" '. selected( @$options["theme_custom_bg"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	if ( trim(@$options["theme_bg_pattern"]) !== "" ) {
		$bg_pattern_url = 'background: url(' . get_template_directory_uri(). '/images/patterns/' . $options["theme_bg_pattern"] . ')';
	} else {
		$bg_pattern_url = '';
	}

	$html .= '<div id="ts-patterns-option" class="ts-custom-bg">
				<p>'.esc_html__( 'Please select pattern by clicking on image', 'videofly' ).'</p>

				<a class="thickbox" title="'.esc_html__( 'Click on pattern, then click OK button', 'videofly' ).'" href="'. esc_url( admin_url('admin.php?page=videofly&tab=load_patterns&height=480&width=960') ) . '">
					<div style="width:100px; height:100px; ' . $bg_pattern_url . '" id="pattern-demo">&nbsp;</div>
				</a>

				<input type="hidden" name="videofly_styles[theme_bg_pattern]" value="' . esc_attr(@$options["theme_bg_pattern"]).'" id="videofly-bg-pattern"/>
	</div>';

	$html .= '<div id="ts-bg-color" class="ts-custom-bg"><p>Background color:</p>';

	$color = isset($options["theme_bg_color"]) ? $options["theme_bg_color"] : '#FFFFFF';

	$html .= '<input type="text" id="theme-bg-color" class="popup-colorpicker" name="videofly_styles[theme_bg_color]" value="' . $color . '"/><div id="ts-theme-bg-picker"></div>
	</div>
	';

	$html .= '<div id="ts-bg-image" class="ts-custom-bg">';
	$html .= '<p>'.esc_html__( 'Upload background image:', 'videofly' ).'</p>';
	$html .= '<input type="text" name="videofly_styles[bg_image]" class="image_url" value="'.esc_url(@$options['bg_image']).'"/>
			<input type="hidden" value="" class="image_media_id"/>
			<input id="ts-upload-bg-image" type="button" name="ts-upload-fb-image" class="button-primary videofly_select_image" value="'.esc_html__('Upload', 'videofly').'">';
	$html .= '</div>';

	echo vdf_var_sanitize($html);
}

function toggle_favicon_callback($args)
{
	$options = get_option('videofly_styles');

	$favicon = esc_url(@$options["favicon"]);

	$html = '<div>
					<input type="text" name="videofly_styles[favicon]" class="image_url" value="'.$favicon.'">
					<input id="ts-upload-favicon" type="button" name="ts-upload-favicon" class="button-primary videofly_select_image" value="'.esc_html__('Upload', 'videofly').'">
					<input type="hidden" value="" class="image_media_id" />';
	$html.= '<p class="description">' .$args[0]. '</p>
				</div>';

	echo vdf_var_sanitize($html);
}

function toggle_facebook_image_callback($args)
{
	$options = get_option('videofly_styles');

	$facebook_image = esc_url(@$options["facebook_image"]);

	$html = '<div>
					<input type="text" name="videofly_styles[facebook_image]" class="image_url" value="'.$facebook_image.'">
					<input id="ts-upload-facebook-image" type="button" name="ts-upload-facebook_image" class="button-primary videofly_select_image" value="'.esc_html__('Upload', 'videofly').'">
					<input type="hidden" value="" class="image_media_id" />';
	$html.= '<p class="description">' .$args[0]. '</p>
				</div>';

	echo vdf_var_sanitize($html);
}

function toggle_overlay_effect_for_images_callback($args)
{
	$options = get_option('videofly_styles');

	$html = '<select name="videofly_styles[overlay_effect_for_images]" id="overlay-effect-for-images">
				<option value="Y" '. selected( @$options["overlay_effect_for_images"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["overlay_effect_for_images"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';

	$html .= '<p class="description">' .$args[0]. '</p>
				<div id="overlay-effects">
				<label for="stripes_effect">
				<input type="radio" id="stripes_effect" name="videofly_styles[overlay_effect_type]" value="stripes"' . checked( @$options["overlay_effect_type"], 'stripes', false ) .'/>'.esc_html__( 'stripes', 'videofly' ) . '</label>
				<label for="dotts_effect">
				<input type="radio" id="dotts_effect" name="videofly_styles[overlay_effect_type]" value="dots" ' . checked( @$options["overlay_effect_type"], 'dots', false ) . ' />'.esc_html__( 'dots', 'videofly' ) .'</label>
				</div>';

	echo vdf_var_sanitize($html);
}

function toggle_scroll_to_top_callback($args)
{
	$options = get_option('videofly_styles');
	$scroll_to_top = (isset($options['scroll_to_top']) && ($options['scroll_to_top'] == 'y' || $options['scroll_to_top'] == 'n')) ? $options['scroll_to_top'] : 'y';

	$html = '<select name="videofly_styles[scroll_to_top]">
				<option value="y" ' . selected($scroll_to_top, 'y', false) . '>' . esc_html__( 'Yes', 'videofly' ) . '</option>
				<option value="n" ' . selected($scroll_to_top, 'n', false) . '>' . esc_html__( 'No', 'videofly' ) . '</option>
			</select>';

	echo vdf_var_sanitize($html);
}

function toggle_style_hover_callback($args)
{
	$options = get_option('videofly_styles');
	$style_hover = (isset($options['style_hover']) && ($options['style_hover'] == 'style1' || $options['style_hover'] == 'style2')) ? $options['style_hover'] : 'style1';

	$html = '<select name="videofly_styles[style_hover]">
				<option value="style1" ' . selected($style_hover, 'style1', false) . '>' . esc_html__( 'Style 1', 'videofly' ) . '</option>
				<option value="style2" ' . selected($style_hover, 'style2', false) . '>' . esc_html__( 'Style 2', 'videofly' ) . '</option>
			</select>';

	echo vdf_var_sanitize($html);
}

function toggle_hover_gallery_callback($args)
{
	$options = get_option('videofly_styles');
	$hover_gallery = (isset($options['hover_gallery']) && ($options['hover_gallery'] == 'open-on-click' || $options['hover_gallery'] == 'slide-from-bottom')) ? $options['hover_gallery'] : 'open-on-click';

	$html = '<select name="videofly_styles[hover_gallery]">
				<option value="open-on-click" ' . selected($hover_gallery, 'open-on-click', false) . '>' . esc_html__( 'Open on click', 'videofly' ) . '</option>
				<option value="slide-from-bottom" ' . selected($hover_gallery, 'slide-from-bottom', false) . '>' . esc_html__( 'Slide on bottom', 'videofly' ) . '</option>
			</select>';

	echo vdf_var_sanitize($html);
}

function toggle_header_position_callback($args)
{
	$options = get_option('videofly_styles');
	$header_position = (isset($options['header_position']) && ($options['header_position'] == 'right' || $options['header_position'] == 'left' || $options['header_position'] == 'top')) ? $options['header_position'] : 'top';

	$html = '<select name="videofly_styles[header_position]">
				<option value="right" ' . selected($header_position, 'right', false) . '>' . esc_html__( 'Right', 'videofly' ) . '</option>
				<option value="top" ' . selected($header_position, 'top', false) . '>' . esc_html__( 'Top', 'videofly' ) . '</option>
				<option value="left" ' . selected($header_position, 'left', false) . '>' . esc_html__( 'Left', 'videofly' ) . '</option>
			</select>';

	echo vdf_var_sanitize($html);
}

function toggle_site_width_callback($args)
{
	$options = get_option('videofly_styles');
	$arrayDeafaultWidth = array('1380', '1240', '1170', '960');
	$siteDefaultWidth = (isset($options['site_width']) && in_array($options['site_width'], $arrayDeafaultWidth)) ? $options['site_width'] : '1380';

	$html = '<select name="videofly_styles[site_width]">
				<option value="1380" ' . selected($siteDefaultWidth, '1380', false) . '>1380</option>
				<option value="1240" ' . selected($siteDefaultWidth, '1240', false) . '>1240</option>
				<option value="1170" ' . selected($siteDefaultWidth, '1170', false) . '>1170</option>
				<option value="960" ' . selected($siteDefaultWidth, '960', false) . '>960</option>
			</select>';

	echo vdf_var_sanitize($html);
}

function toggle_bordered_widgets_callback($args)
{
	$options = get_option('videofly_styles');
	$borderedWidgets = isset($options['bordered_widgets']) ? $options['bordered_widgets'] : 'N';

	$html = '<select name="videofly_styles[bordered_widgets]">
				<option value="Y" ' . selected($borderedWidgets, 'Y', false) . '>' . esc_html__('Yes', 'videofly') . '</option>
				<option value="N" ' . selected($borderedWidgets, 'N', false) . '>' . esc_html__('No', 'videofly') . '</option>
			</select>';

	$html.= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_effect_in_general_callback($args)
{
	$options = get_option('videofly_styles');

	$animationEffects = array(
			esc_html__('None', 'videofly') => 'none',
			esc_html__('Fade in', 'videofly') => 'fade-in',
			esc_html__('Fade out', 'videofly') => 'fade-out',
			esc_html__('Fade in up sm', 'videofly') => 'fade-in-up-sm',
			esc_html__('Fade in up', 'videofly') => 'fade-in-up',
			esc_html__('Fade in up lg', 'videofly') => 'fade-in-up-lg',
			esc_html__('Fade out up sm', 'videofly') => 'fade-out-up-sm',
			esc_html__('Fade out up', 'videofly') => 'fade-out-up',
			esc_html__('Fade out up lg', 'videofly') => 'fade-out-up-lg',
			esc_html__('Fade in down sm', 'videofly') => 'fade-in-down-sm',
			esc_html__('Fade in down', 'videofly') => 'fade-in-down',
			esc_html__('Fade in down lg', 'videofly') => 'fade-in-down-lg',
			esc_html__('Fade out down sm', 'videofly') => 'fade-out-down-sm',
			esc_html__('Fade out down', 'videofly') => 'fade-out-down',
			esc_html__('Fade out down lg', 'videofly') => 'fade-out-down-lg',
			esc_html__('Fade in left sm', 'videofly') => 'fade-in-left-sm',
			esc_html__('Fade in left', 'videofly') => 'fade-in-left',
			esc_html__('Fade in left lg', 'videofly') => 'fade-in-left-lg',
			esc_html__('Fade out left sm', 'videofly') => 'fade-out-left-sm',
			esc_html__('Fade out left', 'videofly') => 'fade-out-left',
			esc_html__('Fade out left lg', 'videofly') => 'fade-out-left-lg',
			esc_html__('Fade in right sm', 'videofly') => 'fade-in-right-sm',
			esc_html__('Fade in right', 'videofly') => 'fade-in-right',
			esc_html__('Fade in right lg', 'videofly') => 'fade-in-right-lg',
			esc_html__('Fade out right sm', 'videofly') => 'fade-out-right-sm',
			esc_html__('Fade out right', 'videofly') => 'fade-out-right',
			esc_html__('Fade out right lg', 'videofly') => 'fade-out-right-lg',
			esc_html__('Rotate in sm', 'videofly') => 'rotate-in-sm',
			esc_html__('Rotate in', 'videofly') => 'rotate-in',
			esc_html__('Rotate in lg', 'videofly') => 'rotate-in-lg',
			esc_html__('Rotate out sm', 'videofly') => 'rotate-out-sm',
			esc_html__('Rotate out', 'videofly') => 'rotate-out',
			esc_html__('Rotate out lg', 'videofly') => 'rotate-out-lg',
			esc_html__('Flip in x fr', 'videofly') => 'flip-in-x-fr',
			esc_html__('Flip in x', 'videofly') => 'flip-in-x',
			esc_html__('Flip in x nr', 'videofly') => 'flip-in-x-nr',
			esc_html__('Flip in y fr', 'videofly') => 'flip-in-y-fr',
			esc_html__('Flip in y', 'videofly') => 'flip-in-y',
			esc_html__('Flip in y nr', 'videofly') => 'flip-in-y-nr',
			esc_html__('Flip out x fr', 'videofly') => 'flip-out-x-fr',
			esc_html__('Flip out x', 'videofly') => 'flip-out-x',
			esc_html__('Flip out x nr', 'videofly') => 'flip-out-x-nr',
			esc_html__('Flip out fr', 'videofly') => 'flip-out-fr',
			esc_html__('Flip out y', 'videofly') => 'flip-out-y',
			esc_html__('Flip out y nr', 'videofly') => 'flip-out-y-nr',
			esc_html__('Zoom in sm', 'videofly') => 'zoom-in-sm',
			esc_html__('Zoom in', 'videofly') => 'zoom-in',
			esc_html__('Zoom in lg', 'videofly') => 'zoom-in-lg',
			esc_html__('Zoom out sm', 'videofly') => 'zoom-out-sm',
			esc_html__('Zoom out', 'videofly') => 'zoom-out',
			esc_html__('Zoom out lg', 'videofly') => 'zoom-out-lg',
			esc_html__('Overlay slide in top', 'videofly') => 'overlay-slide-in-top',
			esc_html__('Overlay slide out top', 'videofly') => 'overlay-slide-out-top',
			esc_html__('Overlay slide in bottom', 'videofly') => 'overlay-slide-in-bottom',
			esc_html__('Overlay slide out bottom', 'videofly') => 'overlay-slide-out-bottom',
			esc_html__('Overlay slide in left', 'videofly') => 'overlay-slide-in-left',
			esc_html__('Overlay slide out left', 'videofly') => 'overlay-slide-out-left',
			esc_html__('Overlay slide in right', 'videofly') => 'overlay-slide-in-right',
			esc_html__('Overlay slide out right', 'videofly') => 'overlay-slide-out-right'

		);

	$animationGeneral = (isset($options['effect_in_general']) && in_array($options['effect_in_general'], $animationEffects)) ? $options['effect_in_general'] : 'none';
	$html = '<select name="videofly_styles[effect_in_general]">';

	foreach($animationEffects as $translateAnimation => $animation){
		$html .= '<option value="'. $animation .'" '. selected($animationGeneral, $animation, false) .'>'. $translateAnimation .'</option>';
	}

	$html .= '</select>';

	$html .= '<p class="description">'. $args[0] .'</p>';
	echo vdf_var_sanitize($html);
}

function toggle_effect_out_general_callback($args)
{
	$options = get_option('videofly_styles');

	$animationEffects = array(
			esc_html__('None', 'videofly') => 'none',
			esc_html__('Fade in', 'videofly') => 'fade-in',
			esc_html__('Fade out', 'videofly') => 'fade-out',
			esc_html__('Fade in up sm', 'videofly') => 'fade-in-up-sm',
			esc_html__('Fade in up', 'videofly') => 'fade-in-up',
			esc_html__('Fade in up lg', 'videofly') => 'fade-in-up-lg',
			esc_html__('Fade out up sm', 'videofly') => 'fade-out-up-sm',
			esc_html__('Fade out up', 'videofly') => 'fade-out-up',
			esc_html__('Fade out up lg', 'videofly') => 'fade-out-up-lg',
			esc_html__('Fade in down sm', 'videofly') => 'fade-in-down-sm',
			esc_html__('Fade in down', 'videofly') => 'fade-in-down',
			esc_html__('Fade in down lg', 'videofly') => 'fade-in-down-lg',
			esc_html__('Fade out down sm', 'videofly') => 'fade-out-down-sm',
			esc_html__('Fade out down', 'videofly') => 'fade-out-down',
			esc_html__('Fade out down lg', 'videofly') => 'fade-out-down-lg',
			esc_html__('Fade in left sm', 'videofly') => 'fade-in-left-sm',
			esc_html__('Fade in left', 'videofly') => 'fade-in-left',
			esc_html__('Fade in left lg', 'videofly') => 'fade-in-left-lg',
			esc_html__('Fade out left sm', 'videofly') => 'fade-out-left-sm',
			esc_html__('Fade out left', 'videofly') => 'fade-out-left',
			esc_html__('Fade out left lg', 'videofly') => 'fade-out-left-lg',
			esc_html__('Fade in right sm', 'videofly') => 'fade-in-right-sm',
			esc_html__('Fade in right', 'videofly') => 'fade-in-right',
			esc_html__('Fade in right lg', 'videofly') => 'fade-in-right-lg',
			esc_html__('Fade out right sm', 'videofly') => 'fade-out-right-sm',
			esc_html__('Fade out right', 'videofly') => 'fade-out-right',
			esc_html__('Fade out right lg', 'videofly') => 'fade-out-right-lg',
			esc_html__('Rotate in sm', 'videofly') => 'rotate-in-sm',
			esc_html__('Rotate in', 'videofly') => 'rotate-in',
			esc_html__('Rotate in lg', 'videofly') => 'rotate-in-lg',
			esc_html__('Rotate out sm', 'videofly') => 'rotate-out-sm',
			esc_html__('Rotate out', 'videofly') => 'rotate-out',
			esc_html__('Rotate out lg', 'videofly') => 'rotate-out-lg',
			esc_html__('Flip in x fr', 'videofly') => 'flip-in-x-fr',
			esc_html__('Flip in x', 'videofly') => 'flip-in-x',
			esc_html__('Flip in x nr', 'videofly') => 'flip-in-x-nr',
			esc_html__('Flip in y fr', 'videofly') => 'flip-in-y-fr',
			esc_html__('Flip in y', 'videofly') => 'flip-in-y',
			esc_html__('Flip in y nr', 'videofly') => 'flip-in-y-nr',
			esc_html__('Flip out x fr', 'videofly') => 'flip-out-x-fr',
			esc_html__('Flip out x', 'videofly') => 'flip-out-x',
			esc_html__('Flip out x nr', 'videofly') => 'flip-out-x-nr',
			esc_html__('Flip out fr', 'videofly') => 'flip-out-fr',
			esc_html__('Flip out y', 'videofly') => 'flip-out-y',
			esc_html__('Flip out y nr', 'videofly') => 'flip-out-y-nr',
			esc_html__('Zoom in sm', 'videofly') => 'zoom-in-sm',
			esc_html__('Zoom in', 'videofly') => 'zoom-in',
			esc_html__('Zoom in lg', 'videofly') => 'zoom-in-lg',
			esc_html__('Zoom out sm', 'videofly') => 'zoom-out-sm',
			esc_html__('Zoom out', 'videofly') => 'zoom-out',
			esc_html__('Zoom out lg', 'videofly') => 'zoom-out-lg',
			esc_html__('Overlay slide in top', 'videofly') => 'overlay-slide-in-top',
			esc_html__('Overlay slide out top', 'videofly') => 'overlay-slide-out-top',
			esc_html__('Overlay slide in bottom', 'videofly') => 'overlay-slide-in-bottom',
			esc_html__('Overlay slide out bottom', 'videofly') => 'overlay-slide-out-bottom',
			esc_html__('Overlay slide in left', 'videofly') => 'overlay-slide-in-left',
			esc_html__('Overlay slide out left', 'videofly') => 'overlay-slide-out-left',
			esc_html__('Overlay slide in right', 'videofly') => 'overlay-slide-in-right',
			esc_html__('Overlay slide out right', 'videofly') => 'overlay-slide-out-right'

		);

	$animationGeneral = (isset($options['effect_out_general']) && in_array($options['effect_out_general'], $animationEffects)) ? $options['effect_out_general'] : 'none';
	$html = '<select name="videofly_styles[effect_out_general]">';

	foreach($animationEffects as $translateAnimation => $animation){
		$html .= '<option value="'. $animation .'" '. selected($animationGeneral, $animation, false) .'>'. $translateAnimation .'</option>';
	}

	$html .= '</select>';

	$html .= '<p class="description">'. $args[0] .'</p>';
	echo vdf_var_sanitize($html);
}

function toggle_logo_type_callback($args)
{
	$options = array(
        'type' => array(
            'image'          => esc_html__('Logo image', 'videofly'),
            'google'       => esc_html__('Logo text', 'videofly')
        ),
        'font_weight'  => '',
        'font_size'    => '',
        'font_style'   => '',
        'text'         => '',
        'font_subsets' => ''
    );
	vdfAddTypographyElement('', 'videofly_styles', 'logo', $options);
}

/**
 * Iniaitalize the theme options colors section by registering the Fields, Sections, Settings
 */
function videofly_initialize_colors_options()
{
	if( false === get_option( 'videofly_colors' ) ) {

		add_option( 'videofly_colors', array(
			'general_text_color' => '#111111',
			'link_color' => '#D91A3C',
			'link_color_hover' => '#000000',
			'view_link_color' => '#000000',
			'view_link_color_hover' => '#656363',
			'meta_color' => '#AEAFB1',
			'primary_color' => '#D91A3C',
			'primary_color_hover' => '#ad102c',
			'secondary_color' => '#000000',
			'secondary_color_hover' => '#24272c',
			'primary_text_color' => '#FFFFFF',
			'primary_text_color_hover' => '#f5f6f7',
			'secondary_text_color' => '#FFFFFF',
			'secondary_text_color_hover' => '#eef4f7',
			'submenu_bg_color' => '#ffffff',
			'submenu_text_color' => '#333333',
			'submenu_bg_color_hover' => '#f4f5f6',
			'submenu_text_color_hover' => '#D91A3C',
			'excerpt_color' => '#474747'
		));

	} // end if

	// Register styles section
	add_settings_section(
		'color_settings_section',
		esc_html__( 'Theme color options', 'videofly' ),
		'videofly_colors_callback',
		'videofly_colors'
	);



	add_settings_field(
		'general_text_color',
		esc_html__( 'General color for the text on the website', 'videofly' ),
		'toggle_videofly_general_text_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'Change this to any color you want and that fits the background of the website.', 'videofly' )
		)
	);

	add_settings_field(
		'general_text_color',
		esc_html__( 'General color for the text on the website', 'videofly' ),
		'toggle_videofly_general_text_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'Change this to any color you want and that fits the background of the website.', 'videofly' )
		)
	);

	add_settings_field(
		'link_color',
		esc_html__( 'Link color', 'videofly' ),
		'toggle_videofly_link_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'Change this color if you want the links on your website to have a different color.', 'videofly' )
		)
	);
	add_settings_field(
		'link_color_hover',
		esc_html__( 'Link color on hover', 'videofly' ),
		'toggle_videofly_link_color_hover_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'Change this color if you want the links on hover to have a different color.', 'videofly' )
		)
	);

	add_settings_field(
		'views_link_color',
		esc_html__( 'Link colors in views (grid/list/bigpost)', 'videofly' ),
		'toggle_videofly_view_link_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'You have different types to showcase your articles. This option will change the color of the links of the articles.', 'videofly' )
		)
	);
	add_settings_field(
		'views_link_color_hover',
		esc_html__( 'Title colors on hover in view', 'videofly' ),
		'toggle_videofly_view_link_color_hover_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'You have different types to showcase your articles. This option will change the color on hover of the titles of the articles.', 'videofly' )
		)
	);
	add_settings_field(
		'meta_color',
		esc_html__( 'Meta text color', 'videofly' ),
		'toggle_videofly_meta_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'Change the color of the text for your meta.', 'videofly' )
		)
	);
	add_settings_field(
		'primary_color',
		esc_html__( 'Primary color', 'videofly' ),
		'toggle_videofly_primary_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'Main color of the website. It is used for backgrounds, borders of elements, etc. This defines your main brand/website color.', 'videofly' )
		)
	);
	add_settings_field(
		'primary_color_hover',
		esc_html__( 'Primary color on hover', 'videofly' ),
		'toggle_videofly_primary_color_hover_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'Main color of the website. It is used for backgrounds, borders of elements, etc. This defines your main brand/website color on hover.', 'videofly' )
		)
	);
	add_settings_field(
		'secondary_color',
		esc_html__( 'Secondary color', 'videofly' ),
		'toggle_videofly_secondary_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'Secondary color of the website. It is used for backgrounds, borders of elements, etc. This defines your secondary or contrast brand/website color.', 'videofly' )
		)
	);
	add_settings_field(
		'secondary_color_hover',
		esc_html__( 'Secondary color on hover', 'videofly' ),
		'toggle_videofly_secondary_color_hover_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'Secondary color of the website. It is used for backgrounds, borders of elements, etc. This defines your secondary or contrast brand/website color on hover.', 'videofly' )
		)
	);
	add_settings_field(
		'primary_text_color',
		esc_html__( 'Primary text color', 'videofly' ),
		'toggle_videofly_primary_text_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'The color of the text that has a primary color background. Primary color reffers to the color setting above.', 'videofly' )
		)
	);
	add_settings_field(
		'primary_text_color_hover',
		esc_html__( 'Primary text color on hover', 'videofly' ),
		'toggle_videofly_primary_text_color_hover_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'The color of the text that has a primary color background on hover. Primary color reffers to the color setting above.', 'videofly' )
		)
	);
	add_settings_field(
		'secondary_text_color',
		esc_html__( 'Secondary text color', 'videofly' ),
		'toggle_videofly_secondary_text_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'The color of the text that has a secondary color background.', 'videofly' )
		)
	);
	add_settings_field(
		'secondary_text_color_hover',
		esc_html__( 'Secondary text color on hover', 'videofly' ),
		'toggle_videofly_secondary_text_color_hover_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'The color of the text that has a secondary color background on hover. Primary color reffers to the color setting above.', 'videofly' )
		)
	);
	add_settings_field(
		'menu_bg_color',
		esc_html__( 'Submenu background color', 'videofly' ),
		'toggle_videofly_submenu_bg_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'This is used for menus that have background colors. Not all menu styles can have backgrounds. Even so, this option will apply for submenu backgrounds as well, even for those that do not have a background by default', 'videofly' )
		)
	);
	add_settings_field(
		'menu_bg_color_hover',
		esc_html__( 'Submenu background color on hover', 'videofly' ),
		'toggle_videofly_submenu_bg_color_hover_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'Same thing as the option above, only for the hover state.', 'videofly' )
		)
	);
	add_settings_field(
		'menu_text_color',
		esc_html__( 'Menu text color', 'videofly' ),
		'toggle_videofly_submenu_text_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'The colors of the text in the menus and submenus.', 'videofly' )
		)
	);
	add_settings_field(
		'menu_text_color_hover',
		esc_html__( 'Menu text color on hover', 'videofly' ),
		'toggle_videofly_submenu_text_color_hover_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'The colors of the text in the menus and submenus on hover.', 'videofly' )
		)
	);

	add_settings_field(
		'excerpt_color',
		esc_html__( 'Excerpt color', 'videofly' ),
		'toggle_excerpt_color_callback',
		'videofly_colors',
		'color_settings_section',
		array(
			esc_html__( 'The colors of the text in the menus and submenus on hover.', 'videofly' )
		)
	);

	register_setting( 'videofly_colors', 'videofly_colors');

} // end videofly_initialize_theme_options


add_action( 'admin_init', 'videofly_initialize_colors_options' );

/**************************************************
 * Colors Section Callbacks
 *************************************************/

function videofly_colors_callback()
{
	echo '<p>'.esc_html__( 'Settings for your website color settings. Here you can change colors that are shown on your website.', 'videofly' ).'</p>';
?>

<?php
} // end videofly_styles_callback

function toggle_videofly_general_text_color_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-general-text-color" class="colors-section-picker" name="videofly_colors[general_text_color]" value="'.esc_attr(@$options["general_text_color"]).'" /><div class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}


function toggle_videofly_link_color_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-link-color" class="colors-section-picker" name="videofly_colors[link_color]" value="'.esc_attr(@$options["link_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_link_color_hover_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-link-hover-color" class="colors-section-picker" name="videofly_colors[link_color_hover]" value="'.esc_attr(@$options["link_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_videofly_view_link_color_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-view-link-color" class="colors-section-picker" name="videofly_colors[view_link_color]" value="'.esc_attr(@$options["view_link_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_view_link_color_hover_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-view-link-hover-color" class="colors-section-picker" name="videofly_colors[view_link_color_hover]" value="'.esc_attr(@$options["view_link_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_meta_color_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-meta-text-color" class="colors-section-picker" name="videofly_colors[meta_color]" value="'.esc_attr(@$options["meta_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_primary_color_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-primary-color" class="colors-section-picker" name="videofly_colors[primary_color]" value="'.esc_attr(@$options["primary_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_primary_color_hover_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-primary-color-hover" class="colors-section-picker" name="videofly_colors[primary_color_hover]" value="'.esc_attr(@$options["primary_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_secondary_color_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-secondary-color" class="colors-section-picker" name="videofly_colors[secondary_color]" value="'.esc_attr(@$options["secondary_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_secondary_color_hover_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-secondary-color-hover" class="colors-section-picker" name="videofly_colors[secondary_color_hover]" value="'.esc_attr(@$options["secondary_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_videofly_primary_text_color_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-primary-text-color" class="colors-section-picker" name="videofly_colors[primary_text_color]" value="'.esc_attr(@$options["primary_text_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_primary_text_color_hover_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-primary-text-color-hover" class="colors-section-picker" name="videofly_colors[primary_text_color_hover]" value="'.esc_attr(@$options["primary_text_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_secondary_text_color_hover_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-secondary-text-color-hover" class="colors-section-picker" name="videofly_colors[secondary_text_color_hover]" value="'.esc_attr(@$options["secondary_text_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_secondary_text_color_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-secondary-text-color" class="colors-section-picker" name="videofly_colors[secondary_text_color]" value="'.esc_attr(@$options["secondary_text_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_submenu_bg_color_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-menu-bg-color" class="colors-section-picker" name="videofly_colors[submenu_bg_color]" value="'.esc_attr(@$options["submenu_bg_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_submenu_bg_color_hover_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-menu-bg-hover-color" class="colors-section-picker" name="videofly_colors[submenu_bg_color_hover]" value="'.esc_attr(@$options["submenu_bg_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_submenu_text_color_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-menu-text-color" class="colors-section-picker" name="videofly_colors[submenu_text_color]" value="'.esc_attr(@$options["submenu_text_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}
function toggle_videofly_submenu_text_color_hover_callback($args)
{
	$options = get_option('videofly_colors');

	$html = '<input type="text" id="ts-menu-text-hover-color" class="colors-section-picker" name="videofly_colors[submenu_text_color_hover]" value="'.esc_attr(@$options["submenu_text_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_excerpt_color_callback($args)
{
	$options = get_option('videofly_colors');
	$excerptColor = (isset($options['excerpt_color']) && !empty($options['excerpt_color'])) ? esc_attr($options['excerpt_color']) : '#808080';
	$html = '<input type="text" class="colors-section-picker" name="videofly_colors[excerpt_color]" value="' . $excerptColor . '" />
			<div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' . $args[0] . '</p>';

	echo vdf_var_sanitize($html);
}

// Typography tab
function videofly_initialize_typography_options()
{
	// delete_option( 'videofly_typography' );
	if( false === get_option( 'videofly_typography' ) ) {

		add_option( 'videofly_typography', array(
			'google_fonts_key' => 'AIzaSyBHh7VPOKMPw1oy6wsEs8FNtR5E8zjb-7A',
			'h1' => array(
				'type' => 'std',
				'font_name' => '0',
				'font_family' => '',
				'font_size'   => '54',
				'font_weight' => '400',
				'font_style' => 'normal',
				'text' => 'Videofly',
				'font_subsets' => array('latin'),
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'standard_font' => 'Roboto'
			),
			'h2' => array(
				'type' => 'std',
				'font_name' => '0',
				'font_family' => '',
				'font_size'   => '44',
				'font_weight' => '400',
				'font_style' => 'normal',
				'text' => 'Videofly',
				'font_subsets' => array('latin'),
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'standard_font' => 'Roboto'
			),
			'h3' => array(
				'type' => 'std',
				'font_name' => '0',
				'font_size'   => '36',
				'font_weight' => '400',
				'font_style' => 'normal',
				'text' => 'Videofly',
				'font_subsets' => array('latin'),
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'standard_font' => 'Roboto'
			),
			'h4' => array(
				'type' => 'std',
				'font_name' => '0',
				'font_family' => '',
				'font_size'   => '28',
				'font_weight' => '400',
				'font_style' => 'normal',
				'text' => 'Videofly',
				'font_subsets' => array('latin'),
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'standard_font' => 'Roboto'
			),
			'h5' => array(
				'type' => 'std',
				'font_name' => '0',
				'font_family' => '',
				'font_size'   => '18',
				'font_weight' => '400',
				'font_style' => 'normal',
				'text' => 'Videofly',
				'font_subsets' => array('latin'),
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'standard_font' => 'Roboto'
			),
			'h6' => array(
				'type' => 'std',
				'font_name' => '0',
				'font_family' => '',
				'font_size'   => '16',
				'font_weight' => '400',
				'font_style' => 'normal',
				'text' => 'Videofly',
				'font_subsets' => array('latin'),
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'standard_font' => 'Montserrat'
			),
			'menu' => array(
				'type' => 'std',
				'font_name' => '0',
				'font_family' => '',
				'font_size'   => '12',
				'font_weight' => '400',
				'font_style' => 'normal',
				'text' => 'Videofly',
				'font_subsets' => array('latin'),
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'standard_font' => 'Roboto'
			),
			'body' => array(
				'type' => 'std',
				'font_name' => '0',
				'font_family' => '',
				'font_size'   => '14',
				'font_weight' => '400',
				'font_style' => 'normal',
				'text' => 'Videofly',
				'font_subsets' => array('latin'),
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'standard_font' => 'Roboto'
			),

			'custom-icon' => array(

			),

			'icons' => 'icon-noicon,icon-image,icon-comments,icon-delete,icon-rss,icon-drag,icon-down,icon-up,icon-layout,icon-import,icon-play,icon-desktop,icon-social,icon-empty,icon-filter,icon-money,icon-flickr,icon-pinterest,icon-user,icon-video,icon-close,icon-link,icon-views,icon-quote,icon-pencil,icon-page,icon-post,icon-category,icon-time,icon-left,icon-right,icon-palette,icon-code,icon-sidebar,icon-vimeo,icon-lastfm,icon-logo,icon-heart,icon-list,icon-attention,icon-menu,icon-delimiter,icon-image-size,icon-settings,icon-share,icon-resize-vertical,icon-text,icon-movie,icon-dribbble,icon-yahoo,icon-facebook,icon-twitter,icon-tumblr,icon-gplus,icon-skype,icon-linkedin,icon-tick,icon-edit,icon-font,icon-home,icon-button,icon-wordpress,icon-music,icon-mail,icon-lock,icon-search,icon-github,icon-basket,icon-star,icon-link-ext,icon-award,icon-signal,icon-target,icon-attach,icon-download,icon-upload,icon-mic,icon-calendar,icon-phone,icon-headphones,icon-flag,icon-credit-card,icon-save,icon-megaphone,icon-key,icon-euro,icon-pound,icon-dollar,icon-rupee,icon-yen,icon-rouble,icon-try,icon-won,icon-bitcoin,icon-anchor,icon-support,icon-blocks,icon-block,icon-graduate,icon-shield,icon-window,icon-coverflow,icon-flight,icon-brush,icon-resize-full,icon-news,icon-pin,icon-params,icon-beaker,icon-delivery,icon-bell,icon-help,icon-laptop,icon-tablet,icon-mobile,icon-thumb,icon-briefcase,icon-direction,icon-ticket,icon-chart,icon-book,icon-print,icon-on,icon-off,icon-featured-area,icon-team,icon-login,icon-clients,icon-tabs,icon-tags,icon-gauge,icon-bag,icon-key,icon-glasses,icon-ok-full,icon-restart,icon-recursive,icon-shuffle,icon-ribbon,icon-lamp,icon-flash,icon-leaf,icon-chart-pie-outline,icon-puzzle,icon-fullscreen,icon-downscreen,icon-zoom-in,icon-zoom-out,icon-pencil-alt,icon-down-dir,icon-left-dir,icon-right-dir,icon-up-dir,icon-circle-outline,icon-circle-full,icon-dot-circled,icon-threedots,icon-colon,icon-down-micro,icon-cancel,icon-medal,icon-square-outline,icon-rhomb,icon-rhomb-outline,icon-featured-article,icon-timer,icon-event-date,icon-chronometers,icon-weights,icon-calligraphy,icon-fast-delivery,icon-education,icon-notes,icon-announce,icon-toggler,icon-home-care,icon-website-star,icon-coconut,icon-supermarket,icon-curved-arrows,icon-telescope,icon-analysis-timer,icon-headphones,icon-search-field,icon-leaves,icon-semicircular,icon-eyeglasses,icon-diagrams,icon-chair,icon-online,icon-writing,icon-mappointer,icon-book,icon-laboratory,icon-heart-hands,icon-presentation,icon-diploma,icon-protected,icon-analysis,icon-goal,icon-plate,icon-analytics,icon-passport,icon-sandwich,icon-website-code,icon-target-hit,icon-network-people,icon-search-engine-optimization,icon-notebook-checked,icon-website-checked,icon-firewall,icon-space-ship,icon-map-pointer,icon-web-programming,icon-christmas-present,icon-login12,icon-login9,icon-road16,icon-fast-forward-button,icon-phone-1,icon-shopping63,icon-newspapers2,icon-business-up,icon-graphic-man,icon-work-graphic,icon-badge-new,icon-christmas-present-1,icon-video-cineva,icon-film-round,icon-images-gallery,icon-photo-tripod,icon-photo-stativ,icon-illumination,icon-photography-umbrella,icon-photo-more,icon-photography-camera,icon-device-camera,icon-music-item,icon-video-surveillance,icon-desk-lamp,icon-usb,icon-mouse-scroll',

		));
	} // end if

	// Register a section
	add_settings_section(
		'typography_settings_section',
		esc_html__( 'Typography Options', 'videofly' ),
		'videofly_typography_callback',
		'videofly_typography'
	);

	add_settings_field(
		'google_fonts_key',
		esc_html__( 'Google fonts API key', 'videofly' ),
		'toggle_google_api_key_callback',
		'videofly_typography',
		'typography_settings_section',
		array(
			__( sprintf('Get your key <a href="%s" target="_blank">%s</a>', 'https://developers.google.com/fonts/docs/developer_api', esc_html__('here', 'videofly') ), 'videofly' )
		)
	);

	$tags_h = array('H1', 'H2', 'H3', 'H4', 'H5', 'H6');
	foreach($tags_h as $tag){
		add_settings_field(
			$tag,
			$tag . esc_html__(' styles', 'videofly' ),
			'toggle_' . $tag . '_callback',
			'videofly_typography',
			'typography_settings_section',
			array(
				esc_html__( 'Change the styles of the heading', 'videofly' )
			)
		);
	}

	add_settings_field(
		'body',
		esc_html__( 'General body text styles', 'videofly' ),
		'toggle_body_callback',
		'videofly_typography',
		'typography_settings_section',
		array(
			esc_html__( 'This is general body settings. This will change the font for the entire website.', 'videofly' )
		)
	);

	add_settings_field(
		'menu_font',
		esc_html__( 'Menu text styles', 'videofly' ),
		'toggle_menu_font_callback',
		'videofly_typography',
		'typography_settings_section',
		array(
			esc_html__( 'This is used for styling the menu element.', 'videofly' )
		)
	);

	add_settings_field(
		'custom-icon',
		esc_html__( 'Add new icon', 'videofly' ),
		'toggle_custom_icon_callback',
		'videofly_typography',
		'typography_settings_section',
		array(
			esc_html__( 'Add your custom icons from fontello.com', 'videofly' )
		)
	);

	register_setting( 'videofly_typography', 'videofly_typography');

} // END videofly_initialize_typography_options

add_action( 'admin_init', 'videofly_initialize_typography_options' );

/**************************************************
 * Typography Section Callbacks
 *************************************************/

function videofly_typography_callback()
{
	echo '<p>'.esc_html__( 'Use settings below to change typography for your website.', 'videofly' ).'</p>';
} // END videofly_typography_callback()

function toggle_H1_callback()
{
	vdfAddTypographyElement('', 'videofly_typography', 'h1', array());
}
function toggle_H2_callback()
{
	vdfAddTypographyElement('', 'videofly_typography', 'h2', array());
}
function toggle_H3_callback()
{
	vdfAddTypographyElement('', 'videofly_typography', 'h3', array());
}
function toggle_H4_callback()
{
	vdfAddTypographyElement('', 'videofly_typography', 'h4', array());
}
function toggle_H5_callback()
{
	vdfAddTypographyElement('', 'videofly_typography', 'h5', array());
}
function toggle_H6_callback()
{
	vdfAddTypographyElement('', 'videofly_typography', 'h6', array());
}
function toggle_menu_font_callback()
{
	vdfAddTypographyElement('', 'videofly_typography', 'menu', array());
}
function toggle_body_callback()
{
	vdfAddTypographyElement('', 'videofly_typography', 'body', array());
}

function toggle_google_api_key_callback($args)
{
	$options = get_option('videofly_typography');

	$key = @$options['google_fonts_key'];

	echo '<input type="text" name="videofly_typography[google_fonts_key]" id="videofly_google_fonts_key" value="'.esc_attr($key).'"/><p class="description">' .@$args[0]. '</p>';
}

function toggle_custom_icon_callback($args)
{
	//delete_option('videofly_typography');
	$options = get_option('videofly_typography');

	$icons = isset($options['custom-icon']) && !empty($options['custom-icon']) ? $options['custom-icon'] : '';

	if( !empty($icons) ){
		foreach($icons as $key => $value){
			echo '<div class="ts-icon-custom">
					<span class="ts-show-icon">'. esc_html__('Block icons', 'videofly') .' '. ((int)$key + 1) .'</span>
					<ul class="builder-icon-list ts-custom-selector ts-icon-container">';
						$classes = explode(',', $value['classes']);
						foreach($classes as $class){
							echo 	'<li data-icon="'. $class .'">
										<i class="'. $class .' clickable-element"></i>
									</li>';
						}
			echo 	'</ul>
					<input type="button" class="button-primary ts-delete-icon" value="'. esc_html__('Delete icon', 'videofly') .'">'.
					(count($classes) > 1 ? '<input type="button" data-key="'. $key .'" class="button-primary ts-delete-icons" value="'. esc_html__('Delete all icons', 'videofly') .'">' : '') .'
				</div>';
		}
	}

	echo
		'<div>
			<span>'. esc_html__('Upload file fontello.svg', 'videofly') .'</span>
			<input type="button" class="button-primary ts-upload" value="Upload">
			<input type="text" name="" value="">
			<input type="hidden" value="" name="ts-svg">
		</div>
		<div>
			<span>'. esc_html__('Upload file fontello.eot', 'videofly') .'</span>
			<input type="button" class="button-primary ts-upload" value="Upload">
			<input type="text" name="" value="">
			<input type="hidden" name="ts-eot" value="">
		</div>
		<div>
			<span>'. esc_html__('Upload file fontello.ttf', 'videofly') .'</span>
			<input type="button" class="button-primary ts-upload" value="Upload">
			<input type="text" name="" value="">
			<input type="hidden" name="ts-ttf" value="">
		</div>
		<div>
			<span>'. esc_html__('Upload file fontello.woff', 'videofly') .'</span>
			<input type="button" class="button-primary ts-upload" value="Upload">
			<input type="text" name="" value="">
			<input type="hidden" name="ts-woff" value="">
		</div>
		<div>
			<span>'. esc_html__('Upload file fontello.css', 'videofly') .'</span>
			<input type="button" class="button-primary ts-upload" value="Upload">
			<input type="text" name="" value="">
			<input type="hidden" name="ts-css" value="">
		</div>
		<p class="description">'. @$args[0] .'</p>';
}

function videofly_single_post_options()
{
	if( false === get_option( 'videofly_single_post' ) ) {
		add_option( 'videofly_single_post', array() );

		$data = array(
			'related_posts' => 'Y',
			'number_of_related_posts' => 4,
			'related_posts_nr_of_columns' => 4,
			'related_posts_type' => 'thumbnails',
			'related_posts_selection_criteria' => 'by_tags',
			'social_sharing' => 'Y',
			'post_tags' => 'Y',
			'post_meta' => 'Y',
			'post_pagination' => 'Y',
			'show_more' => 'y',
			'display_author_box' => 'n',
			'breadcrumbs' => 'y',
			'single_layout_video' => 'single_style1',
			'article_progress' => 'Y',
			'sticky_sidebars' => 'Y',
			'views' => 'N',
			'log_video' => 'Y'
		);

		update_option('videofly_single_post', $data);
	}

	add_settings_section(
		'single_post_settings_section',
		esc_html__( 'Single post Options', 'videofly' ),
		'videofly_single_post_callback',
		'videofly_single_post'
	);

	add_settings_field(
		'related_posts',
		esc_html__( 'Enable related posts', 'videofly' ),
		'toggle_related_posts_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			esc_html__( 'Settings for related posts on single posts', 'videofly' )
		)
	);

	add_settings_field(
		'social_sharing',
		esc_html__( 'Social sharing', 'videofly' ),
		'toggle_social_sharing_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			esc_html__( 'Enable social sharing on single posts.', 'videofly' )
		)
	);

	add_settings_field(
		'post_meta',
		esc_html__( 'Display post meta', 'videofly' ),
		'toggle_post_meta_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			esc_html__( 'Use this option to show or hide meta in posts.', 'videofly' )
		)
	);

	add_settings_field(
		'post_tags',
		esc_html__( 'Display post tags', 'videofly' ),
		'toggle_post_tags_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			esc_html__( 'Show or hide tags in single posts.', 'videofly' )
		)
	);

	add_settings_field(
		'post_pagination',
		esc_html__( 'Display pagination in single post', 'videofly' ),
		'toggle_post_pagination_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			esc_html__( 'Show or hide pagination in single posts.', 'videofly' )
		)
	);

	add_settings_field(
		'display_author_box',
		esc_html__( 'Hide author box', 'videofly' ),
		'toggle_display_author_box_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			esc_html__( 'You can globally author box on your single posts.', 'videofly' )
		)
	);

	add_settings_field(
		'breadcrumbs',
		esc_html__( 'Breadcrumbs:', 'videofly' ),
		'toggle_breadcrumbs_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			esc_html__( 'Activate or disable breadcrumbs on your website.', 'videofly' )
		)
	);

	add_settings_field(
		'single_layout_video',
		esc_html__( 'Choose layout for video posts', 'videofly' ),
		'toggle_layout_video_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			esc_html__( 'Choose layout for existing video post and to be added.', 'videofly' )
		)
	);

	add_settings_field(
		'sticky_sidebars',
		esc_html__( 'Enable sticky sidebars', 'videofly' ),
		'toggle_sticky_sidebars_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			esc_html__( 'If enabled will make sidebars sticky on scroll and will stick them inside the parent container. This options will not be enabled on mobile devices.', 'videofly' )
		)
	);

	add_settings_field(
		'views',
		esc_html__( 'Enable show views on the site', 'videofly' ),
		'toggle_views_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			esc_html__( 'If enabled will show views on the site.', 'videofly' )
		)
	);

	add_settings_field(
		'article_progress',
		esc_html__( 'Enable article progress bar', 'videofly' ),
		'toggle_article_progress_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			esc_html__( 'If enabled will add a progress bar on the top of the browser which shows how much of the article has been read.', 'videofly' )
		)
	);

	add_settings_field(
		'log_video',
		__( 'Show video users not loggeded', 'videofly' ),
		'toggle_log_video_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array(
			''
		)
	);

	add_settings_field(
		'download',
		esc_html__( 'Display download link if video is uploaded by user (mp4 upload)', 'videofly' ),
		'toggle_download_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array('')
	);

	add_settings_field(
		'video_scroll',
		esc_html__( 'Create a small video player when scrolling below the main player', 'videofly' ),
		'toggle_video_scroll_callback',
		'videofly_single_post',
		'single_post_settings_section',
		array('')
	);

	register_setting( 'videofly_single_post', 'videofly_single_post');

} // end videofly_single_post_options()

add_action( 'admin_init', 'videofly_single_post_options' );

/**************************************************
 * Single post Section Callbacks
 *************************************************/

function videofly_single_post_callback()
{
	echo '<p>'.esc_html__( 'Single posts settings options. In this section you can enable/disable related posts, social sharing, tags.', 'videofly' ).'</p>';
} // end videofly_single_post_callback()

function toggle_related_posts_callback($args)
{
	$options = get_option('videofly_single_post');

	$html = '<select name="videofly_single_post[related_posts]" class="ts-related-posts">
				<option value="Y" '. selected( @$options["related_posts"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["related_posts"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';

	$html .= '<p class="description">' .@$args[0]. '</p>';

	$number_of_related_posts = (int)@$options['number_of_related_posts'];
	$number_of_related_posts = ($number_of_related_posts < 1) ? 3 : $number_of_related_posts;

	$related_posts_nr_of_columns = (int)@$options['related_posts_nr_of_columns'];

	$html .= '<div id="ts-related-posts-options">';

	$html .= '<p>'.esc_html__( 'Number of related posts', 'videofly' ).'</p>';

	$html .= '<select name="videofly_single_post[number_of_related_posts]">
		<option value="2" '.selected( $number_of_related_posts, '2', false ). '>2</option>
		<option value="3" '.selected( $number_of_related_posts, '3', false ). '>3</option>
		<option value="4" '.selected( $number_of_related_posts, '4', false ). '>4</option>
		<option value="6" '.selected( $number_of_related_posts, '6', false ). '>6</option>
		<option value="9" '.selected( $number_of_related_posts, '9', false ). '>9</option>
	</select>';

	$html .= '<p>' . esc_html__( 'Number of columns', 'videofly' ) . '</p>';

	$html .= '<select name="videofly_single_post[related_posts_nr_of_columns]">
				<option value="2" '.selected( $related_posts_nr_of_columns, '2', false ). '>2</option>
				<option value="3" '.selected( $related_posts_nr_of_columns, '3', false ). '>3</option>
				<option value="4" '.selected( $related_posts_nr_of_columns, '4', false ). '>4</option>
			</select>';

	$html .= '<p>' . esc_html__( 'Post type', 'videofly' ) . '</p>';

	$html .= '<select name="videofly_single_post[related_posts_type]">
				<option value="grid" '. selected( @$options["related_posts_type"], 'grid', false ).'>'.esc_html__( 'Grid', 'videofly' ).'</option>
				<option value="thumbnails" '. selected( @$options["related_posts_type"], 'thumbnails', false ). '>'.esc_html__( 'Thumbnail', 'videofly' ).'</option>
			</select>';

	$html .= '<p>'.esc_html__( 'Selection criteria', 'videofly' ).'</p>';

	$html .= '<select name="videofly_single_post[related_posts_selection_criteria]">
				<option value="by_tags" '. selected( @$options["related_posts_selection_criteria"], 'by_tags', false ).'>'.esc_html__( 'by Tags', 'videofly' ).'</option>
				<option value="by_categs" '. selected( @$options["related_posts_selection_criteria"], 'by_categs', false ). '>'.esc_html__( 'by Categories', 'videofly' ).'</option>
			</select>';
	$html .= '</div>';

	echo vdf_var_sanitize($html);

} // END toggle_related_posts_callback()

function toggle_social_sharing_callback($args)
{

	$options = get_option('videofly_single_post');

	$html = '<select name="videofly_single_post[social_sharing]">
				<option value="Y" '. selected( @$options["social_sharing"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["social_sharing"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo vdf_var_sanitize($html);

} // END toggle_social_sharing_callback()

function toggle_post_meta_callback($args)
{
	$options = get_option('videofly_single_post');

	$html = '<select name="videofly_single_post[post_meta]">
				<option value="Y" '. selected( @$options["post_meta"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["post_meta"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo vdf_var_sanitize($html);

} // END toggle_post_meta_callback()

function toggle_post_tags_callback($args)
{
	$options = get_option('videofly_single_post');

	$html = '<select name="videofly_single_post[post_tags]">
				<option value="Y" '. selected( @$options["post_tags"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["post_tags"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo vdf_var_sanitize($html);

} // toggle_post_tags_callback()

function toggle_display_author_box_callback($args)
{
	$options = get_option('videofly_single_post');

	$html = '<select name="videofly_single_post[display_author_box]">
				<option value="y" ' . selected(@$options["display_author_box"], 'y', false) .'>'.esc_html__( 'Yes', 'videofly' ) . '</option>
				<option value="n" ' . selected(@$options["display_author_box"], 'n', false) . '>'.esc_html__( 'No', 'videofly' ) . '</option>
			</select>';
	$html .= '<p class="description">' . @$args[0] . '</p>';

	echo vdf_var_sanitize($html);

}

function toggle_breadcrumbs_callback($args)
{
	$options = get_option('videofly_single_post');

	$html = '<select name="videofly_single_post[breadcrumbs]">
				<option value="y" ' . selected(@$options["breadcrumbs"], 'y', false) .'>'.esc_html__( 'Yes', 'videofly' ) . '</option>
				<option value="n" ' . selected(@$options["breadcrumbs"], 'n', false) . '>'.esc_html__( 'No', 'videofly' ) . '</option>
			</select>';
	$html .= '<p class="description">' . @$args[0] . '</p>';

	echo vdf_var_sanitize($html);

}

function toggle_layout_video_callback($args)
{
	$options = get_option('videofly_single_post');
	$layout = isset($options['single_layout_video']) ? $options['single_layout_video'] : 'single_style1';

	$html = '<select name="videofly_single_post[single_layout_video]">
				<option value="single_style1" '. selected($layout, 'single_style1', false) .'>'.esc_html__( 'Style 1', 'videofly' ) .'</option>
				<option value="single_style2" '. selected($layout, 'single_style2', false) .'>'.esc_html__( 'Style 2', 'videofly' ) .'</option>
			</select>';
	$html .= '<p class="description">' . @$args[0] . '</p>';

	echo vdf_var_sanitize($html);

}

function toggle_post_pagination_callback($args)
{
	$options = get_option('videofly_single_post');

	$html = '<select name="videofly_single_post[post_pagination]">
				<option value="Y" '. selected( @$options["post_pagination"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["post_pagination"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo vdf_var_sanitize($html);

} // toggle_post_pagination_callback()

function toggle_sticky_sidebars_callback($args)
{
	$options = get_option('videofly_single_post');

	$html = '<select name="videofly_single_post[sticky_sidebars]">
				<option value="Y" '. selected( @$options["sticky_sidebars"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["sticky_sidebars"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo vdf_var_sanitize($html);

}

function toggle_views_callback($args)
{
	$options = get_option('videofly_single_post');
	$views = isset($options['views']) ? $options['views'] : 'N';

	$html = '<select name="videofly_single_post[views]">
				<option value="Y" '. selected($views, 'Y', false) .'>'. esc_html__( 'Yes', 'videofly' ) .'</option>
				<option value="N" '. selected($views, 'N', false) .'>'. esc_html__( 'No', 'videofly' ) .'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo vdf_var_sanitize($html);

}

function toggle_article_progress_callback($args)
{
	$options = get_option('videofly_single_post');

	$html = '<select name="videofly_single_post[article_progress]">
				<option value="Y" '. selected( @$options["article_progress"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["article_progress"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo vdf_var_sanitize($html);

}

function toggle_log_video_callback($args)
{
	$options = get_option( 'videofly_single_post' );
	$log_video = isset( $options['log_video'] ) ? $options['log_video'] : 'Y';

	$html = '<div>
	            <select name="videofly_single_post[log_video]">
	                <option value="Y" ' . selected( $log_video, 'Y', false ) . '>' . esc_html__( 'Yes', 'videofly' ) . '</option>
	                <option value="N" ' . selected( $log_video, 'N', false ) . '>' . esc_html__( 'No', 'videofly' ) . '</option>
	            </select>
	         </div>';
	$html .= '<p class="description">' . $args[0] . '</p>';

	echo vdf_var_sanitize( $html );
}

function toggle_download_callback($args)
{
	$options = get_option('videofly_single_post');
	$download = isset($options["download"]) ? $options["download"] : 'N';

	$html = '<select name="videofly_single_post[download]">
				<option value="Y" '. selected($download, 'Y', false) .'>'. esc_html__( 'Yes', 'videofly' ) .'</option>
				<option value="N" '. selected($download, 'N', false) .'>'. esc_html__( 'No', 'videofly' ) .'</option>
			</select>';
	$html .= '<p class="description">'. @$args[0] .'</p>';

	echo vdf_var_sanitize($html);

}

function toggle_video_scroll_callback($args)
{
	$options = get_option('videofly_single_post');
	$video_scroll = isset($options["video_scroll"]) ? $options["video_scroll"] : 'Y';

	$html = '<select name="videofly_single_post[video_scroll]">
				<option value="Y" '. selected($video_scroll, 'Y', false) .'>'. esc_html__( 'Yes', 'videofly' ) .'</option>
				<option value="N" '. selected($video_scroll, 'N', false) .'>'. esc_html__( 'No', 'videofly' ) .'</option>
			</select>';
	$html .= '<p class="description">'. @$args[0] .'</p>';

	echo vdf_var_sanitize($html);

}

function videofly_page_options()
{
	//delete_option('videofly_page');
	if( false === get_option( 'videofly_page' ) ) {
		add_option( 'videofly_page' );

		$data = array(
			'social_sharing' => 'Y',
			'post_meta' => 'Y',
			'breadcrumbs' => 'y'
		);

		update_option('videofly_page', $data);
	}

	// Register a section
	add_settings_section(
		'page_settings_section',
		esc_html__( 'Page options', 'videofly' ),
		'videofly_page_callback',
		'videofly_page'
	);

	add_settings_field(
		'social_sharing',
		esc_html__( 'Social sharing', 'videofly' ),
		'toggle_page_social_sharing_callback',
		'videofly_page',
		'page_settings_section',
		array(
			esc_html__( 'This will enable/disable social sharing buttons on pages.', 'videofly' )
		)
	);

	add_settings_field(
		'post_meta',
		esc_html__( 'Display page meta', 'videofly' ),
		'toggle_page_post_meta_callback',
		'videofly_page',
		'page_settings_section',
		array(
			esc_html__( 'Show/hide page meta', 'videofly' )
		)
	);

	add_settings_field(
		'breadcrumbs',
		esc_html__( 'Breadcrumbs', 'videofly' ),
		'toggle_page_breadcrumbs_callback',
		'videofly_page',
		'page_settings_section',
		array(
			esc_html__( 'Show/hide page meta', 'videofly' )
		)
	);
	register_setting( 'videofly_page', 'videofly_page');

} // end videofly_page_options

add_action( 'admin_init', 'videofly_page_options' );

/**************************************************
 * Single post Section Callbacks
 *************************************************/

function videofly_page_callback()
{

	echo '<p>'.esc_html__( 'In this section you can change settings for pages, to enable/disable page meta and social sharing.', 'videofly' ).'</p>';
} // END videofly_page_callback

function toggle_page_social_sharing_callback($args)
{
	$options = get_option('videofly_page');

	$html = '<select name="videofly_page[social_sharing]">
				<option value="Y" '. selected( @$options["social_sharing"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["social_sharing"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_page_post_meta_callback($args)
{
	$options = get_option('videofly_page');

	$html = '<select name="videofly_page[post_meta]">
				<option value="Y" '. selected( @$options["post_meta"], 'Y', false ).'>'.esc_html__( 'Yes', 'videofly' ).'</option>
				<option value="N" '. selected( @$options["post_meta"], 'N', false ). '>'.esc_html__( 'No', 'videofly' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_page_breadcrumbs_callback($args)
{
	$options = get_option('videofly_page');

	$html = '<select name="videofly_page[breadcrumbs]">
				<option value="y" ' . selected( @$options["breadcrumbs"], 'y', false ) .'>' . esc_html__( 'Yes', 'videofly' ) . '</option>
				<option value="n" ' . selected( @$options["breadcrumbs"], 'n', false ) . '>' . esc_html__( 'No', 'videofly' ) . '</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function videofly_social_options()
{		$x = get_option('videofly_social');

	$social = array();
	if( isset($_POST) && isset($_POST['videofly_social']['social-new']) && !empty($_POST['videofly_social']['social-new']) && is_array($_POST['videofly_social']['social-new']) ){
		foreach ($_POST['videofly_social']['social-new'] as $key => $value) {
			$url = (isset($value['url'])) ? esc_attr($url) : '';
			$image = (isset($value['image'])) ? esc_url($image) : '';
			$color = (isset($value['color'])) ? esc_attr($color) : '';
			$social[]['url'] = $url;
			$social[]['image'] = $image;
			$social[]['color'] = $color;
		}
	}
	if( false === get_option( 'videofly_social' ) ) {
		add_option( 'videofly_social' );

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

		update_option('videofly_social', $data);
	}

	add_settings_section(
		'social_section',
		esc_html__( 'Social icons options', 'videofly' ),
		'videofly_social_callback',
		'videofly_social'
	);

	add_settings_field(
		'email',
		esc_html__( 'Email', 'videofly' ),
		'toggle_email_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'This email is used to receive emails from contact form', 'videofly' )
		)
	);

	add_settings_field(
		'skype',
		esc_html__( 'Skype', 'videofly' ),
		'toggle_skype_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your Skype here', 'videofly' )
		)
	);

	add_settings_field(
		'github',
		esc_html__( 'Github', 'videofly' ),
		'toggle_github_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your github page here', 'videofly' )
		)
	);

	add_settings_field(
		'gplus',
		esc_html__( 'Google+', 'videofly' ),
		'toggle_google_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your Google+ page here.', 'videofly' )
		)
	);

	add_settings_field(
		'dribble',
		esc_html__( 'Dribble', 'videofly' ),
		'toggle_dribble_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your Dribbble page here.', 'videofly' )
		)
	);

	add_settings_field(
		'lastfm',
		esc_html__( 'last.fm', 'videofly' ),
		'toggle_lastfm_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your last.fm page here.', 'videofly' )
		)
	);

	add_settings_field(
		'linkedin',
		esc_html__( 'LinkedIn', 'videofly' ),
		'toggle_linkedin_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your LinkedIn here.', 'videofly' )
		)
	);

	add_settings_field(
		'tumblr',
		esc_html__( 'Tumblr', 'videofly' ),
		'toggle_tumblr_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your Tumblr page here.', 'videofly' )
		)
	);

	add_settings_field(
		'twitter',
		esc_html__( 'Twitter', 'videofly' ),
		'toggle_twitter_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your Twitter page here.', 'videofly' )
		)
	);

	add_settings_field(
		'vimeo',
		esc_html__( 'Vimeo', 'videofly' ),
		'toggle_vimeo_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your Vimeo page here.', 'videofly' )
		)
	);

	add_settings_field(
		'wordpress',
		esc_html__( 'WordPress', 'videofly' ),
		'toggle_wordpress_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your WordPress page here.', 'videofly' )
		)
	);

	add_settings_field(
		'yahoo',
		esc_html__( 'Yahoo', 'videofly' ),
		'toggle_yahoo_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your Yahoo ID here.', 'videofly' )
		)
	);

	add_settings_field(
		'youtube',
		esc_html__( 'Youtube', 'videofly' ),
		'toggle_youtube_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your YouTube page here.', 'videofly' )
		)
	);

	add_settings_field(
		'facebook',
		esc_html__( 'Facebook', 'videofly' ),
		'toggle_facebook_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your Facebook page here.', 'videofly' )
		)
	);

	add_settings_field(
		'flickr',
		esc_html__( 'Flickr', 'videofly' ),
		'toggle_flickr_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your Flickr page here.', 'videofly' )
		)
	);

	add_settings_field(
		'pinterest',
		esc_html__( 'Pinterest', 'videofly' ),
		'toggle_pinterest_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your Pinterest page here.', 'videofly' )
		)
	);

	add_settings_field(
		'instagram',
		esc_html__( 'Instagram', 'videofly' ),
		'toggle_instagram_social_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your Pinterest page here.', 'videofly' )
		)
	);

	add_settings_field(
		'social-new',
		esc_html__( 'Add new', 'videofly' ),
		'toggle_social_new_callback',
		'videofly_social',
		'social_section',
		array(
			esc_html__( 'Insert your new social page here.', 'videofly' )
		)
	);

	register_setting( 'videofly_social', 'videofly_social');

} // END videofly_social_options

add_action( 'admin_init', 'videofly_social_options' );

function videofly_theme_update_options()
{
	//delete_option('videofly_theme_update');
	if( false === get_option( 'videofly_theme_update' ) ) {
		add_option( 'videofly_theme_update' );

		$data = array(
			'update_options'=> array(
				'user_name' => '',
				'key_api'   => ''
			)
		);

		update_option('videofly_theme_update', $data);
	}

	add_settings_section(
		'theme_update_section',
		esc_html__( 'Update your Theme from the WordPress Dashboard', 'videofly' ),
		'videofly_theme_update_callback',
		'videofly_theme_update'
	);

	register_setting( 'videofly_theme_update', 'videofly_theme_update');

}

add_action( 'admin_init', 'videofly_theme_update_options' );

function videofly_theme_advertising_options()
{
	//delete_option('videofly_theme_advertising');

	if( false === get_option( 'videofly_theme_advertising' ) ) {
		add_option( 'videofly_theme_advertising' );

		$data = array(
				'ad_area_1'   => '',
				'ad_area_2'   => '',
				'adver_video' => array()
		);

		update_option('videofly_theme_advertising', $data);
	}

	add_settings_section(
		'theme_advertising_section',
		esc_html__( 'Advertising code', 'videofly' ),
		'videofly_theme_advertising_callback',
		'videofly_theme_advertising'
	);

	add_settings_field(
		'ad_area_1',
		esc_html__( 'Area 1', 'videofly' ),
		'videofly_add_area_1_callback',
		'videofly_theme_advertising',
		'theme_advertising_section',
		array(
			esc_html__( 'This advertising will be shown <b>above the video</b> on the video single post. Used only for custom video posts.', 'videofly' )
		)
	);

	add_settings_field(
		'ad_area_2',
		esc_html__( 'Area 2', 'videofly' ),
		'videofly_add_area_2_callback',
		'videofly_theme_advertising',
		'theme_advertising_section',
		array(
			esc_html__( 'This advertising will be shown <b>above the comments</b> on the single post. Used only for any theme posts types.', 'videofly' )
		)
	);

	add_settings_field(
		'adver_video',
		__( 'Add pre advertising video', 'videofly' ),
		'toggle_advertising_video_callback',
		'videofly_theme_advertising',
		'theme_advertising_section',
		array()
	);

	register_setting( 'videofly_theme_advertising', 'videofly_theme_advertising');

}
add_action( 'admin_init', 'videofly_theme_advertising_options' );

/**************************************************
 * Advertising Section Callbacks
 *************************************************/

function videofly_theme_advertising_callback()
{

	$html   = '';
	echo vdf_var_sanitize($html);
}

function videofly_add_area_1_callback($args)
{
	$options = get_option('videofly_theme_advertising');
	$html    = '<textarea name="videofly_theme_advertising[ad_area_1]" cols="80" rows="10">' . @$options['ad_area_1'] . '</textarea>';
	$html   .= '<p class="description">' . @$args[0] . '</p>';
	echo vdf_var_sanitize($html);
}

function videofly_add_area_2_callback($args)
{
	$options = get_option('videofly_theme_advertising');
	$html    = '<textarea name="videofly_theme_advertising[ad_area_2]" cols="80" rows="10">' . @$options['ad_area_2'] . '</textarea>';
	$html   .= '<p class="description">' . @$args[0] . '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_advertising_video_callback($args)
{
	$options = get_option('videofly_theme_advertising');
	$statics = get_option('ad-statistic');
	$statics = !empty($statics) ? $statics : array();
	$html = '';
	$i = 1;

	function vdf_adver_content($option, $i, $key, $statics = array()){

		return
			'<li id="list-item-id-'. $key .'" class="ts-multiple-add-list-element">
				<div class="sortable-meta-element" style="cursor:pointer;">
            		<span class="tab-arrow icon-down" style="margin-left:20px;"></span>
            		<span class="social-item-tab ts-multiple-item-tab">' .
            			__('Video advertising:', 'videofly') . ' ' . $i .
            		'</span>
             	</div>
    			<div class="hidden">
			        <table>
			            <tr>
			                <td>'. esc_html__('Type', 'videofly') .'</td>
			                <td>
			               		<select name="videofly_theme_advertising[adver_video]['. $key .'][type]" class="vdf-adver-type">
			               			<option value="video"'. selected((isset($option['type']) ? $option['type'] : 'video'), 'video', false) .'>'.
			               				__('Pre-roll', 'videofly') .'
			               			</option>
			               			<option value="text"'. selected((isset($option['type']) ? $option['type'] : 'video'), 'text', false) .'>'.
			               				__('Text over', 'videofly') .'
			               			</option>
			               			<option value="image"'. selected((isset($option['type']) ? $option['type'] : 'video'), 'image', false) .'>'.
			               				__('Image over', 'videofly') .'
			               			</option>
			               		</select>
			                </td>
			            </tr>
			            <tr class="vdf-type-video">
			                <td>'. esc_html__('Set video', 'videofly') .'</td>
			                <td>
			                    <input type="text" name="videofly_theme_advertising[adver_video][' . $key . '][videoUrl]" value="'. ( isset($option['videoUrl']) ? $option['videoUrl'] : '' ) .'"/>
			                    <input type="button" data-library="webm" data-title="' . esc_html__('Select video mp4', 'videofly') . '" class="button ts-upload-advertising" value="'. esc_html__( 'Upload video mp4', 'videofly' ) .'"/>
			                </td>
			            </tr>
			            <tr class="vdf-type-image">
			                <td>'. esc_html__('Set image', 'videofly') .'</td>
			                <td>
			                    <input type="text" name="videofly_theme_advertising[adver_video][' . $key . '][imageUrl]" value="'. ( isset($option['imageUrl']) ? $option['imageUrl'] : '' ) .'"/>
			                    <input type="button" data-library="image" data-title="' . esc_html__('Select image', 'videofly') . '" class="button ts-upload-advertising" value="'. esc_html__( 'Set image.', 'videofly' ) .'"/>
			                </td>
			            </tr>
			            <tr class="vdf-type-text">
			                <td>'. esc_html__('Text', 'videofly') .'</td>
			                <td>
			                	<textarea name="videofly_theme_advertising[adver_video][' . $key . '][text]">'. ( isset($option['text']) ? $option['text'] : '' ) .'</textarea>
			                </td>
			            </tr>
			            <tr class="vdf-type-text-image">
			                <td>
			                    '. esc_html__('Star time in format: xx:xx', 'videofly') .'
			                </td>
			                <td>
			                    <input value="'. ( isset($option['start']) ? $option['start'] : '' ) .'" type="text" name="videofly_theme_advertising[adver_video]['. $key .'][start]" />
			                </td>
			            </tr>
			            <tr class="vdf-type-text-image">
			                <td>
			                    '. esc_html__('End time in format: xx:xx', 'videofly') .'
			                </td>
			                <td>
			                    <input value="'. ( isset($option['end']) ? $option['end'] : '' ) .'" type="text" name="videofly_theme_advertising[adver_video]['. $key .'][end]" />
			                </td>
			            </tr>
			            <tr class="vdf-type-video">
			                <td>'. esc_html__('Add button skip', 'videofly') .'</td>
			                <td>
			                    <select name="videofly_theme_advertising[adver_video]['. $key .'][skip]">
			               			<option value="y"'. selected((isset($option['skip']) ? $option['skip'] : 'y'), 'y', false) .'>'.
			               				__('Yes', 'videofly') .'
			               			</option>
			               			<option value="n"'. selected((isset($option['skip']) ? $option['skip'] : 'y'), 'n', false) .'>'.
			               				__('No', 'videofly') .'
			               			</option>
			               		</select>
			                </td>
			            </tr>
			            <tr>
			                <td>'. esc_html__('Link to redirect', 'videofly') .'</td>
			                <td>
			                    <input value="'. ( isset($option['link']) ? esc_url($option['link']) : '' ) .'" type="text" name="videofly_theme_advertising[adver_video]['. $key .'][link]" />
			                </td>
			            </tr>
			            <tr>
			                <td>'. esc_html__('Show ad by criterion', 'videofly') .'</td>
			                <td>
			                    <select name="videofly_theme_advertising[adver_video]['. $key .'][criterion]" class="vdf-adver-criterion">
			               			<option value="categories"'. selected((isset($option['criterion']) ? $option['criterion'] : 'categories'), 'categories', false) .'>'.
			               				__('Categories', 'videofly') .'
			               			</option>
			               			<option value="tags"'. selected((isset($option['criterion']) ? $option['criterion'] : 'categories'), 'tags', false) .'>'.
			               				__('Tags', 'videofly') .'
			               			</option>
			               			<option value="videoIds"'. selected((isset($option['criterion']) ? $option['criterion'] : 'categories'), 'videoIds', false) .'>'.
			               				__('Video ids', 'videofly') .'
			               			</option>
			               		</select>
			                </td>
			            </tr>
			            <tr class="vdf-criterion-tags">
			                <td>
			                    '. esc_html__('Insert slug tags in format: tagslug1,tagslug2,...', 'videofly') .'
			                </td>
			                <td>
			                	<textarea name="videofly_theme_advertising[adver_video][' . $key . '][tags]">'. ( isset($option['tags']) ? $option['tags'] : '' ) .'</textarea>
			                </td>
			            </tr>
			            <tr class="vdf-criterion-categories">
			                <td>
			                    '. esc_html__('Insert slug category in format: slugcategory1,slugcategory2,....', 'videofly') .'
			                </td>
			                <td>
			                	<textarea name="videofly_theme_advertising[adver_video][' . $key . '][category]">'. ( isset($option['category']) ? $option['category'] : '' ) .'</textarea>
			                </td>
			            </tr>
			            <tr class="vdf-criterion-videoIds">
			                <td>
			                    '. esc_html__('Insert video ids in format: id1,id2,....', 'videofly') .'
			                </td>
			                <td>
			                	<textarea name="videofly_theme_advertising[adver_video][' . $key . '][videoIds]">'. ( isset($option['videoIds']) ? $option['videoIds'] : '' ) .'</textarea>
			                </td>
			            </tr>
			            <tr>
			                <td>
			                    '. esc_html__('Exclude video ids in format: id1,id2,....', 'videofly') .'
			                </td>
			                <td>
			                	<textarea name="videofly_theme_advertising[adver_video][' . $key . '][excludeIds]">'. ( isset($option['excludeIds']) ? $option['excludeIds'] : '' ) .'</textarea>
			                </td>
			            </tr>
			            <tr>
			                <td>'. esc_html__('Choose count mode', 'videofly') .'</td>
			                <td>
			                    <select name="videofly_theme_advertising[adver_video]['. $key .'][countMode]" class="vdf-adver-countMode">
			               			<option value="views"'. selected((isset($option['countMode']) ? $option['countMode'] : 'views'), 'views', false) .'>'.
			               				__('Views', 'videofly') .'
			               			</option>
			               			<option value="clicks"'. selected((isset($option['countMode']) ? $option['countMode'] : 'views'), 'clicks', false) .'>'.
			               				__('Clicks', 'videofly') .'
			               			</option>
			               		</select>
			                </td>
			            </tr>
			            <tr class="vdf-countMode-views">
			                <td>
			                    '. esc_html__('Max views', 'videofly') .'
			                </td>
			                <td>
			                	<input value="'. ( isset($option['maxviews']) ? $option['maxviews'] : '' ) .'" type="text" name="videofly_theme_advertising[adver_video]['. $key .'][maxviews]"/>
			                </td>
			            </tr>
			            <tr class="vdf-countMode-clicks">
			                <td>'. esc_html__('Max clicks', 'videofly') .'</td>
			                <td>
			                	<input value="'. ( isset($option['maxclicks']) ? $option['maxclicks'] : '' ) .'" type="text" name="videofly_theme_advertising[adver_video]['. $key .'][maxclicks]"/>
			                </td>
			            </tr>
			            <tr>
			                <td>
			                    '. esc_html__('Views', 'videofly') .'
			                </td>
			                <td>'.
			                	(array_key_exists($key, $statics) ? $statics[$key]['views'] : 0) .'
			                </td>
			            </tr>
			            <tr>
			                <td>
			                    '. esc_html__('Clicks', 'videofly') .'
			                </td>
			                <td>'.
			                	(array_key_exists($key, $statics) ? $statics[$key]['clicks'] : 0) .'
			                </td>
			            </tr>
			            <tr>
			                <td>
			                    '. esc_html__('Active', 'videofly') .'
			                </td>
			                <td>
			                	<select name="videofly_theme_advertising[adver_video]['. $key .'][active]">
			                		<option value="y" '. selected(( isset($option['active']) ? $option['active'] : 'y' ), 'y', false) .'>'
			                			. esc_html__('Yes', 'videofly') .'
			                		</option>
			                		<option value="n" '. selected(( isset($option['active']) ? $option['active'] : 'y' ), 'n', false) .'>'
			                			. esc_html__('No', 'videofly') .'
			                		</option>
			                	</select>
			                </td>
			            </tr>
			        </table>
	        		<input type="button" class="button button-primary remove-item" value="' . esc_html__('Remove', 'videofly') . '" /></div>
	        	</div>
	       	</li>';
	}

	$adver_video = isset($options['adver_video']) && is_array($options['adver_video']) && !empty($options['adver_video']) ? $options['adver_video'] : array();

	foreach ( $statics as $keyAd => $staticsAd ) {
		if ( !array_key_exists($keyAd, $adver_video) ) unset($statics[$keyAd]);
	}

	update_option('ad-statistic', $statics);

	$html = '<ul>';

		foreach ( $adver_video as $key => $option ) {

			$html .= vdf_adver_content($option, $i, $key, $statics);

		    $i++;
		}

	$html .= '</ul>';

	$html .=
			'<ul id="advertising_items">
 			</ul>
	 		<input type="hidden" id="advertising_content" value="" />
 			<input type="button" class="button ts-multiple-add-button" data-element-name="advertising" id="advertising_add_item" value="'. esc_html__('Add new video advertising.', 'videofly') .'" />
 			<script id="advertising_items_template" type="text/template">';

	$html .= vdf_adver_content(array(), $i, '{{item-id}}');

	$html .= '</script>';

    $html .= '<p class="description">'. @$args[0] .'</p>';
	echo vdf_var_sanitize($html);
}
/**************************************************
 * Single post Section Callbacks
 *************************************************/
function videofly_social_callback()
{
	echo '<p>'.esc_html__( 'Insert your link to the social pages below. These are used for social icons. The email set here is going to be used for contact forms.', 'videofly' ).'</p>';
} // END videofly_social_callback

function toggle_email_callback($args)
{
	$options = get_option('videofly_social');
	$email = is_email(@$options['email']) ? @$options['email'] : '';

	$html = '<input type="text" name="videofly_social[email]" value="'. $email . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_skype_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[skype]" value="'. @esc_url($options['skype']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_github_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[github]" value="'. @esc_url($options['github']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_google_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[gplus]" value="'. @esc_url($options['gplus']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_dribble_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[dribble]" value="'. @esc_url($options['dribble']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_lastfm_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[lastfm]" value="'. @esc_url($options['lastfm']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}


function toggle_linkedin_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[linkedin]" value="'. @esc_url($options['linkedin']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_tumblr_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[tumblr]" value="'. @esc_url($options['tumblr']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_twitter_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[twitter]" value="'. @esc_url($options['twitter']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_vimeo_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[vimeo]" value="'. @esc_url($options['vimeo']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_wordpress_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[wordpress]" value="'. @esc_url($options['wordpress']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_yahoo_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[yahoo]" value="'. @esc_url($options['yahoo']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_youtube_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[youtube]" value="'. @esc_url($options['youtube']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_facebook_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[facebook]" value="'. @esc_url($options['facebook']) . '">';
	$html .= '<p class="description">' .$args[0]. '</p>';

	echo vdf_var_sanitize($html);
}

function toggle_flickr_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[flickr]" value="'. @esc_url($options['flickr']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_pinterest_social_callback($args)
{
	$options = get_option('videofly_social');

	$html = '<input type="text" name="videofly_social[pinterest]" value="'. @esc_url($options['pinterest']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}

function toggle_instagram_social_callback($args)
{
	$options = get_option('videofly_social');
	$instagram = (isset($options['instagram']) && !empty($options['instagram'])) ? esc_url($options['instagram']) : '';

	$html = '<input type="text" name="videofly_social[instagram]" value="'. $instagram .'">';
	$html .= '<p class="description">'. @$args[0] .'</p>';
	echo vdf_var_sanitize($html);
}

function toggle_social_new_callback($args)
{
	$options = get_option('videofly_social');
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
		            		<span class="tab-arrow icon-down"></span> <span class="social-item-tab ts-multiple-item-tab">' . esc_html__('Social item:', 'videofly') . ' ' . $i .'</span>
		             	</div>
		    			<div class="hidden">
					        <table>
					             <tr>
					               <td>'.esc_html__( 'Social icon','videofly' ).'</td>
					               <td>
					                     <input id="" type="text" data-role="media-url" name="videofly_social[social_new][' . $i . '][image]" value="' . $image_url . '"/>
					                     <input id="ts_upload-' . $i . '" type="button" class="button ts-upload-social-image ts-multiple-item-upload" value="' . esc_html__( 'Upload','videofly' ) . '" />
					                 </td>
					             </tr>
					             <tr>
					                 <td>
					                     <label for="social-url">Enter url social here:</label>
					                 </td>
					                 <td>
					                    <input value ="' .  $url_social . '" type="text" name="videofly_social[social_new][' .  $i . '][url]" />
					                 </td>
					             </tr>
					             <tr>
					                 <td>
					                     <label for="social-color">Color hover:</label>
					                 </td>
					                 <td>
					                     <input type="text" value="' . $color . '" class="colors-section-picker" name="videofly_social[social_new][' . $i . '][color]" />
					                     <div class="colors-section-picker-div"></div>
					                 </td>
					             </tr>
					        </table>
			        		<input type="button" class="button button-primary remove-item" value="' . esc_html__('Remove', 'videofly') . '" /></div>
			        	</div>
			       	</li>';

		    $i++;
		}
		$html .= '</ul>';
	}

	$html .= '<ul id="social_items">
 			</ul>
	 		<input type="hidden" id="social_content" value="" />
 			<input type="button" class="button ts-multiple-add-button" data-element-name="social" id="social_add_item" value="' . esc_html__('Add New Icon', 'videofly') . '" />';
 	$html .= '<script id="social_items_template" type="text/template">
	     		<li id="list-item-id-{{item-id}}" class="social-item ts-multiple-add-list-element">
		            <div class="sortable-meta-element">
		            	<span class="tab-arrow icon-down"></span> <span class="social-item-tab ts-multiple-item-tab">' . esc_html__('Item:', 'videofly') . ' {{slide-number}}</span>
		            </div>
		            <div class="hidden">

			        <table>
			             <tr>
			               <td>'.esc_html__( 'Social icon','videofly' ).'</td>
			               <td>
			                    <input type="text" data-role="media-url" name="videofly_social[social_new][{{item-id}}][image]" id="social-{{item-id}}-image" value=""/>
			                    <input type="button" id="uploader_{{item-id}}"  class="button ts-upload-social-image ts-multiple-item-upload" value="' . esc_html__( 'Upload','videofly' ) . '" />
			                 </td>
			             </tr>
			             <tr>
			                 <td>
			                     <label for="social-{{item-id}}-url">' . esc_html__('Enter url social here:', 'videofly') . '</label>
			                 </td>
			                 <td>
			                    <input type="text" name="videofly_social[social_new][{{item-id}}][url]" />
			                 </td>
			             </tr>
			             <tr>
			                 <td>
			                     <label for="social-color">' . esc_html__('Color hover:', 'videofly') . '</label>
			                 </td>
			                 <td>
			                     <input type="text" value="#777" class="colors-section-picker" name="videofly_social[social_new][{{item-id}}][color]" />
			                     <div class="colors-section-picker-div" id="social-{{item-id}}-color-picker"></div>
			                 </td>
			             </tr>
			        </table>
		        	<input type="button" class="button button-primary remove-item" value="' . esc_html__('Remove', 'videofly') . '" /></div>
	     		</li>
     		</script>';
    $html .= '<p class="description">' .@$args[0]. '</p>';
	echo vdf_var_sanitize($html);
}


/**************************************************
 * Single post Section Callbacks
 *************************************************/

function videofly_sidebars_options()
{
	if( false === get_option( 'videofly_sidebars' ) ) {
		add_option( 'videofly_sidebars' );
		update_option( 'videofly_sidebars', array() );
	}

	// Register a section
	add_settings_section(
		'sidebars_section',
		esc_html__( 'Sidebars', 'videofly' ),
		'videofly_sidebars_callback',
		'videofly_sidebars'
	);

	register_setting( 'videofly_sidebars', 'videofly_sidebars');

} // END videofly_sidebars_options()

add_action( 'admin_init', 'videofly_sidebars_options' );

/**************************************************
 * Sidebars Section Callbacks
 *************************************************/

function videofly_sidebars_callback()
{
	echo '<p>'.esc_html__( 'Manage your theme sidebars from here', 'videofly' ).'</p>';

	$sidebars = get_option('videofly_sidebars');
	$html = '';

	if (isset($sidebars)) {
		$html .= '<table cellpadding="10" id="ts-sidebars">';

		foreach ($sidebars as $id => $sidebar) {
			$html .= '
			<tr>
				<td class="dynamic-sidebar">'.$sidebar. '</td>
				<td><a href="#" id="'.$id.'" class="ts-remove-sidebar">'.esc_html__( 'Delete', 'videofly' ).'</a></td>
			</tr>';
		}
		$html .= '</table>';
	}

	$html .= '
		<input type="text" name="sidebar_name" id="ts_sidebar_name" />
		<input type="submit" name="add_sidebar" id="ts_add_sidebar" class="button-primary" value="'.esc_html__( 'Add sidebar', 'videofly' ).'" />
		<br/><br/><br/>';
	echo vdf_var_sanitize($html);

} // END videofly_sidebars_callback()

function videofly_init_impots_options()
{
	if( false === get_option( 'videofly_impots_options' ) ) {
		add_option( 'videofly_impots_options', array() );
	}

	// Register a section
	add_settings_section(
		'videofly_impots_options_section',
		esc_html__( 'Import Options', 'videofly' ),
		'videofly_impots_options_callback',
		'videofly_impots_options'
	);

	add_settings_field(
		'import_demo',
		esc_html__( 'Import demo', 'videofly' ),
		'videofly_import_demo_callback',
		'videofly_impots_options',
		'videofly_impots_options_section',
		array(
			esc_html__( 'Import demo settings', 'videofly' )
		)
	);

	add_settings_field(
		'reset_settings',
		esc_html__( 'Reset settings', 'videofly' ),
		'videofly_reset_settings_callback',
		'videofly_impots_options',
		'videofly_impots_options_section',
		array(
			esc_html__( 'Reset your settings to default.', 'videofly' )
		)
	);

	register_setting( 'videofly_impots_options', 'videofly_impots_options');

}

add_action( 'admin_init', 'videofly_init_impots_options' );

function videofly_impots_options_callback($args)
{
	$file_data = '';

	$file_headers = @get_headers(get_template_directory_uri() . '/import/demo-files/settings.txt');
	if($file_headers[0] !== 'HTTP/1.1 404 Not Found') {
		$file_data = wp_remote_fopen(get_template_directory_uri() . '/import/demo-files/settings.txt');
	}

	echo sprintf( '<p>' . esc_html__( 'Proceed with caution. Warning! You %s WILL lose all your current settings FOREVER %s if you paste the import data and click "Save changes". Double check everything!', 'videofly' ) . '</p>', '<b style="color: #E75750">', '</b>');

	if (isset($_GET['updated'])) {
		if ($_GET['updated'] === 'true') {
			echo '<div class="sucess">' . esc_html__('Options are successfully imported', 'videofly').'</div>';
		} else {
			echo '<div class="error">' . esc_html__('Options can\'t be imported. Inserted data can\'t be decoded properly', 'videofly').'</div>';
		}
	}
?><br>
	<form action="<?php echo esc_url( admin_url('admin.php?page=videofly&tab=save_options') ); ?>" method="POST">
		<textarea data-import-demo="<?php echo vdf_var_sanitize($file_data); ?>" name="encoded_options" id="ts_encoded_options" cols="30" rows="10"><?php echo esc_attr(videofly_exports_options()); ?></textarea>
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

function videofly_import_demo_callback(){

	$import = new Ts_Importer();
	$import->demo_installer(); ?>
	<script>
		jQuery('.ts-importer-wrap').siblings().remove();
	</script>
<?php
}

function videofly_reset_settings_callback(){
	if( isset($_POST['reset-settings']) ){
		$expots_options = array(
			'videofly_general',
			'videofly_image_sizes',
			'videofly_layout',
			'videofly_colors',
			'videofly_styles',
			'videofly_typography',
			'videofly_single_post',
			'videofly_page',
			'videofly_social',
			'inline_style',
			'videofly_sidebars',
			'videofly_header',
			'videofly_header_templates',
			'videofly_header_template_id',
			'videofly_footer',
			'videofly_footer_templates',
			'videofly_footer_template_id',
			'videofly_footer_template_id',
			'videofly_page_template_id',
			'videofly_theme_advertising',
			'videofly_theme_update'
		);

		foreach ($expots_options as $option) {
			delete_option($option);
		}
	}
?>
	<form action="<?php echo esc_url( admin_url('admin.php?page=videofly&tab=impots_options') ); ?>" method="POST">
		<input type="submit" name="reset-settings" class="button" value="<?php esc_html_e('Reset settings', 'videofly'); ?>">
	</form>
<?php
}

// ========================================================================================
// TouchSize news and alerts ==============================================================
// ========================================================================================

function videofly_red_area()
{
	if( false === get_option( 'videofly_red_area' ) ) {
		$data = array(
			'news' => '',
			'alert' => array(
				'id' => 0,
				'message' => ''
			),
			'hidden_alerts' => array(),
			'time' => time()
		);

		add_option( 'videofly_red_area', $data );
	}

	// Register a section
	add_settings_section(
		'videofly_red_area',
		esc_html__( 'Red Area', 'videofly' ),
		'videofly_red_area_callback',
		'videofly_red_area'
	);

	register_setting( 'videofly_red_area', 'videofly_red_area');

}

add_action( 'admin_init', 'videofly_red_area' );

function videofly_red_area_callback() {

	echo '<div class="red-last-news">';
	echo '<h4>'.esc_html__( 'Latest news', 'videofly' ).'</h4>';

	$options = get_option('videofly_red_area', array());

	if (isset($options['news'])) {
		echo vdf_var_sanitize($options['news']);
	}
	echo '</div>';
}

function videofly_theme_update_callback(){

	echo '<p>'.esc_html__( 'Update your Theme from the WordPress Dashboard', 'videofly' ).'</p>';

	$theme_update_options = get_option('videofly_theme_update');

	$theme_update = (isset($theme_update_options['update_options'])) ? $theme_update_options['update_options'] : '';

	$html = '<p>In order to be able to do updates directly from the WP Dashboard, you will need to add your ThemeForest details in the form below. If you do not know where to get your API key from, here is a link:<br><br><a href="http://support.touchsize.com/knowledgebase/How-to-get-ThemeForest-API-key-for-updates-58.html" class="button button-secondary" target="_blank">Click for API Key Tutorial</a><br><br></p>';
	$html .= '<h4>Add your ThemeForest information below:</h4>';
	$html .= '<p>Your Themeforest User Name:</p>
	          <input type="text" name="videofly_theme_update[update_options][user_name]" value="'.  trim(esc_attr($theme_update['user_name'])) .'" />';

	$html .= '<p>Your Themeforest API Key:</p>
	          <input type="text" name="videofly_theme_update[update_options][key_api]" value="'.  trim(esc_attr($theme_update['key_api'])) .'" />';

	if($update = check_for_theme_update()){
		$html .= '<p>You have update for your theme</p>';
	}

	echo vdf_var_sanitize($html);
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
