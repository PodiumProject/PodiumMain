<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_DB_Options_Class' ) ) :

abstract class eXc_DB_Options_Class
{
    /**
     * Extracoding Framework Instance
     *
     * @since 1.0
     * @var object
     */
    protected $eXc;

    /**
     * Form Configuration file path
     *
     * @since 1.0
     * @var string
     */
    protected $config_file;

    /**
    * Form ajax action
    *
    * @since 1.0
    * @var string
    */
    protected $action = 'exc_framework_form';

    /**
     * Contain the form configuration file data
     *
     * @since 1.0
     * @var object
     */
    protected $form_settings = array();

    //abstract protected function register_admin_menu();

    public function __construct( &$eXc )
    {
        $this->eXc = $eXc;

        if ( is_admin() )
        {
            // Load Settings when admin is ready
            add_action( 'admin_init', array( &$this, 'load_settings' ) );

            // Filter to load default settings of the form
            add_filter( 'exc-prepare-form', array( &$this, 'prepare_form' ) );
        }
    }

    public function load_settings()
    {
        if ( empty( $this->config_file ) )
        {
            exc_die(
                sprintf(
                    __('The config file name is required in class %s', 'exc-framework'),
                    '<strong>' . get_class( $this ) . '</strong>'
                )
            );
        }

        // Get Form Settings
        if ( empty( $this->form_settings )
                && ( ! $this->form_settings = $this->eXc->load('core/form_class')->get_config( $this->config_file ) ) )
        {
            // Load the form settings
            $this->form_settings = $this->eXc->load_config_file( $this->config_file );
            $this->eXc->form->prepare_fields( $this->form_settings );
        }

        if ( empty( $this->form_settings['action'] ) )
        {
            exc_die(
                sprintf(
                    __('The Ajax callback action is required in %s', 'exc-framework'),
                    '<strong>' . $this->config_file . '</strong>'
                )
            );
        }

        // Ajax callback to save settings
        add_action( 'wp_ajax_' . sanitize_key( $this->form_settings['action'] ), array( &$this, 'save_options' ) );
    }

    public function prepare_form( $form_name = '' )
    {
        if ( $form_name == str_replace( '/', '_', $this->config_file ) )
        {
            $form_settings = $this->eXc->form->get_config( $form_name );

            // Quick hack for saved data processing
            if ( count( $_POST ) == 0 )
            {
                //check if we have db_name
                if ( ! $db_name = exc_kv( $form_settings, 'db_name' ) )
                {
                    wp_die( sprintf( __('The db_name is not defined in %s', 'exc-framework' ), $form_settings['_path'] ) );
                }

                // fetch the user saved values
                if ( $settings = get_option( $db_name, array() ) )
                {
                    //Assign only the values we have in config file
                    $data = array();

                    foreach ( $this->eXc->form->get_fields_list( $form_name ) as $k => $v )
                    {
                        $field_name = ( ! empty( $v->config['dynamic_name'] ) ) ? $v->config['dynamic_name'] : $v->config['name'];

                        if ( isset( $settings[ $field_name ] ) )
                        {
                            if ( isset( $data[ $field_name ] ) )
                            {
                                continue;
                            }

                            $data[ $field_name ] = $settings[ $field_name ];

                            if ( $v->is_dynamic )
                            {
                                $dyn_data = apply_filters( 'exc_dynamic_fields_data', $settings[ $field_name ], $field_name );
                                $this->eXc->html->localize_script( 'exc-dynamic-fields', 'exc_dynamic_fields', array( $v->is_dynamic => $dyn_data ) );
                            }
                        }
                    }

                    $this->eXc->validation->set_data( $data );
                    $this->eXc->form->apply_validation( $form_name );

                } else //first time save values
                {
                    $data = array();

                    foreach ( $this->eXc->form->get_fields_list() as $k => $v )
                    {
                        $field_name = ( ! empty( $v->config['dynamic_name'] ) ) ? $v->config['dynamic_name'] : $v->config['name'];

                        if ( isset( $data[ $field_name ] ) )
                        {
                            continue;
                        }

                        if ( $v->is_dynamic )
                        {
                            $this->eXc->html->localize_script( 'exc-dynamic-fields', 'exc_dynamic_fields', array( $v->is_dynamic => array() ) );
                            $data[ $field_name ] = array();

                        } else
                        {
                            $data[ $field_name ] = $v->set_value();
                        }
                    }

                    $this->eXc->validation->set_data( $data );

                    if ( FALSE !== $this->eXc->form->apply_validation( $form_name ) && ( $db_name = exc_kv( $form_settings, 'db_name' ) ) )
                    {
                        update_option( $db_name, $data );
                    }
                }
            }
        }

        return $form_name;
    }

    public function save_options()
    {
        //$this->eXc->form->prepare_fields( $this->form_settings );

        // stop execution if we have errors in form validation
        if ( count( $this->eXc->validation->_error_array ) )
        {
            $this->eXc->validation->custom_error( 'error_in_form',
                __( 'There are errors in form submission, please double check your entries and try again.', 'exc-framework' ) );

            wp_send_json_error( $this->eXc->validation->errors_array() );
        }

        if ( ! $db_name = exc_kv( $this->form_settings, 'db_name' ) )
        {
            wp_send_json_error( __('Database name is not defined in config file.', 'exc-framework') );
        }

        if ( ! $settings = get_option( $db_name ) )
        {
            $settings = array();
        }

        // change only the values which are submited
        $is_post = count( $_POST ) || false;
        $styles = array();

        $fields =& $this->eXc->form->get_fields_list( $this->form_settings['_name'] );

        foreach ( ( array ) $fields as $field )
        {
            $field_name = ( ! empty( $field->config['dynamic_name'] ) ) ? $field->config['dynamic_name'] : $field->config['name'];

            //$value = ( $is_post ) ? exc_kv( $_POST, $field->config['name'], $field->config['default'] ) : $field->set_value();
            $value = ( $is_post ) ? exc_kv( $_POST, $field_name ) : $field->set_value();

            // Attach value
            // @TODO: move in seperate function and add support for prepend
            if ( $value && isset( $field->config['append'] ) )
            {
                if ( ! is_array( $field->config['append'] ) )
                {
                    $field->config['append'] = ( array ) explode(', ', $field->config['append'] );
                }

                foreach ( $field->config['append'] as $append_key )
                {
                    $append_value = ( ! isset( $fields->{ $append_key } ) ) ? $append_key : $fields->{ $append_key }->set_value();

                    //if ( $append_value )
                    //{
                    if ( is_array( $value ) )
                    {
                        $value[] = $append_value;
                    } elseif ( $append_value || is_numeric( $append_value ) )
                    {
                        $value = $value . ' ' . $append_value;
                    }
                    //}
                }
            }

            if ( isset( $field->config['css_selector'] ) )
            {
                $group = ( ! empty( $field->config['style_opt_key'] ) ) ? $this->eXc->get_product_name() . '_' . $field->config['style_opt_key'] : $this->eXc->get_product_name();

                if ( ! $prop_name = trim( exc_kv( $field->config, 'prop_name' ) ) )
                {
                    continue;
                }

                $selector = preg_replace( '@\s+@', ' ', $field->config['css_selector'] );

                if ( strpos( $prop_name, '%' ) )
                {
                    $prop_value = $value;

                    if ( ! is_array( $prop_value ) )
                    {
                        $prop_value = array_filter( (array) explode( ' ', $prop_value ) );
                    }

                    $semicolon = ( substr( $prop_name, -1 ) != ';' ) ? ';' : '';

                    $styles[ $group ][ $selector ][ $prop_name ] = ( ! empty( $prop_value ) ) ? vsprintf( $prop_name, $prop_value ) . $semicolon : '';

                } elseif ( ! empty( $value ) )
                {
                    $prop_value = ( is_array( $value ) ) ? implode( ' ', $value ) : $value;
                    $styles[ $group ][ $selector ][ $prop_name ] = $prop_name . ': ' . $prop_value . ';';
                } else
                {
                    $styles[ $group ][ $selector ][ $prop_name ] = '';
                }
            }

            $settings[ $field_name ] = $value;
        }

        $settings = apply_filters( "exc_options_{$db_name}", $settings, $this->form_settings, $is_post );

        //@TODO: keep the track of _active_form or form_key to automatically remove depreciated rules
        if ( ! empty( $styles ) )
        {
            // Update Style
            foreach ( $styles as $style_group => $style )
            {
                $opt_name = sanitize_title( $style_group . '_style' );
                $opt_array_name = sanitize_title( $opt_name . '_array' );

                $opt_value = get_option( $opt_array_name, array() );

                $opt_value = array_replace_recursive( $opt_value, $style );

                //create or update style and cache it
                $css = '';
                foreach ( $opt_value as $ok => $ov )
                {
                    $has_value = false;

                    $props = '';

                    foreach ( $ov as $prop )
                    {
                        if ( $prop )
                        {
                            $has_value = true;
                            $props .= $prop;
                        }
                    }

                    if ( $has_value )
                    {
                        $css .= $ok.'{';
                        $css .= $props;
                        $css .= '}'."\n";

                    } else
                    {
                        //Value is removed so clear it
                        unset( $opt_value[ $ok ] );
                    }
                }

                $css = apply_filters( "exc_options_style_{$db_name}", $css );
                $opt_value = apply_filters( "exc_options_style_arra_{$db_name}", $opt_value );

                update_option( $opt_name, $css );
                update_option( $opt_array_name, $opt_value );
            }
        }

        update_option( $db_name, $settings );

        wp_send_json_success( __( 'The options are saved successfully.', 'exc-framework' ) );
    }

    public function enqueue_files()
    {
        // load stylesheet file
        $this->eXc->load('core/html_class')->load_bootstrap()
            ->load_css( 'theme-options-style', $this->eXc->system_url('views/css/style.css') )
            ->load_css( 'font-awesome', $this->eXc->system_url('views/css/font-awesome.min.css') )
            ->load_js( 'exc-theme-options', $this->eXc->system_url('views/js/theme-options.js') );
    }
}

endif;