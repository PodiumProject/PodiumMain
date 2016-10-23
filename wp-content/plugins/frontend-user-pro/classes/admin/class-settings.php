<?php
if ( !class_exists( "USER_Settings" ) ) {

    class USER_Settings {

        public $args = array();
        public $sections = array();
        public $ReduxFrameworkNew;

        public function __construct() {

            if ( !class_exists( "ReduxFrameworkNew" ) ) {
                require_once( dirname( __FILE__ ) . '/redux/ReduxCore/framework.php' );
            }
              $this->initSettings();
        }

        public function user_get_pages( $post_type = 'page' ) {
            global $wpdb;

            $array = array();
            $pages = get_posts( array('post_type' => $post_type, 'numberposts' => -1) );
            if ( $pages ) {
                foreach ($pages as $page) {
                    $array[$page->ID] = $page->post_title;
                }
            }

            return $array;
        }

        public function initSettings() {
            // Set the default arguments
            $this->setArguments();

            // Create the sections and fields
            $this->setSections();

            if ( !isset( $this->args['opt_name'] ) ) { // No errors please
                return;
            }

            $this->ReduxFrameworkNew = new ReduxFrameworkNew( $this->sections, $this->args );
        }


        public function setSections() {
            $pages =$this->user_get_pages();
            $this->sections[] = array(
                'title' => __( 'Main Settings', 'frontend_user_pro' ),
                'desc' => __( 'Frontend User is a constantly improving piece of complex software. For the latest information, update information, and submitting feature requests, visit: <a href="http://frontenduser.com">http://frontenduser.com</a>', 'frontend_user_pro' ),
                'icon' => 'el-icon-home',
                'fields' => array(
                    array(
                        'id'=> 'user-use-css',
                        'type' => 'switch',
                        'title' => __( 'Use USER\'s CSS', 'frontend_user_pro' ),
                        'default'   => true,
                    ),
                    array(
                        'id'=> 'user-user-edit',
                        'type' => 'switch',
                        'title' => __( 'Users can edit post?', 'frontend_user_pro' ),
                        'default'   => false,
                        'desc' => __('Users will be able to edit their own posts' , 'frontend_user_pro'),
                    ),
                    array(
                        'id'=> 'user-user-delete',
                        'type' => 'switch',
                        'title' => __( 'User can delete post?', 'frontend_user_pro' ),
                        'default'   => false,
                        'desc' => __('Users will be able to delete their own posts' , 'frontend_user_pro'),
                    ),
                    array(
                        'id'=> 'user-post-access',
                        'type' => 'switch',
                        'title' => __( 'Access to Post owner?', 'frontend_user_pro' ),
                        'default'   => false,
                        'desc' => __('Any user can access to post' , 'frontend_user_pro'),
                    ),                    
                    array(
                        'id' => 'user-post-per-page',
                        'type' => 'text',
                        'title' => __( 'Posts per page', 'frontend_user_pro' ),
                    ),
                    array(
                        'id' => 'user-pending-post-edit',
                        'type' => 'switch',
                        'title' => __( 'Pending Post Edit', 'frontend_user_pro' ),
                        'default' => true,
                    ),
                    array(
                        'id' => 'user-featured-image',
                        'type' => 'switch',
                        'title' => __( 'Show Featured Image', 'frontend_user_pro' ),
                        'default' => false,
                    ),
                    array(
                        'id'        => 'user-edit-page',
                        'type'      => 'select',
                        'data'      => 'posts',
                        'args'      => array( 
                                        'post_type'     => array('page'), 
                                        'posts_per_page'=>-1, 
                                        'orderby'       => 'post_type',
                                        ),
                        'title'     => __( 'Edit Page', 'frontend_user_pro' ),
                        'desc'      => __( 'Select the page where [wpfeup-edit-form] is locatedSelect the page where [wpfeup-edit-form] is located' , 'frontend_user_pro' ),
                    ),
                )
            );
           
            $this->sections[] = array(
                'icon' => 'el-icon-list-alt',
                'title' => __( 'Forms', 'frontend_user_pro' ),
                'desc' => __( '<p class="description">Settings for USER Forms</p>', 'frontend_user_pro' ),
                'fields' => array(
                    array(
                        'id'        => 'user-login-url',
                        'type'      => 'select',
                        'data'      => 'posts',
                        'args'      => array( 
                                        'post_type'     => array('post','page'), 
                                        'posts_per_page'=>-1, 
                                        'orderby'       => 'post_type',
                                        ),
                        'title'     => __( 'Login Url', 'frontend_user_pro' ),
                    ),
                    array(
                        'id'        => 'user-logout-redirect',
                        'type'      => 'select',
                        'data'      => 'posts',
                        'args'      => array( 
                                        'post_type'     => array('post','page'), 
                                        'posts_per_page'=>-1, 
                                        'orderby'       => 'post_type',
                                        ),
                        'title'     => __( 'After Logout Redirect', 'frontend_user_pro' ),
                    ),
                    array(
                        'id'        => 'user-login-captcha',
                        'type'      => 'switch',
                        'title'     => __( 'CAPTCHA on the login form', 'frontend_user_pro' ),
                        'desc'      => __( 'If on, creates a captcha field on the login form on the user dashboard', 'frontend_user_pro' ),
                        'default'   => false,
                    ),
                    //  array(
                    //     'id'        => 'user-email-verify',
                    //     'type'      => 'switch',
                    //     'title'     => __( 'Email Verification', 'frontend_user_pro' ),
                    //     'desc'      => __( 'Send verification mail after Registration', 'frontend_user_pro' ),
                    //     'default'   => false,
                    // ),
                )
            );
            $this->sections[] = array(
                'icon' => 'el-icon-bullhorn',
                'title' => __( 'Integrations', 'frontend_user_pro' ),
                'desc' => __( '<p class="description">Settings for USER Integrations</p>', 'frontend_user_pro' ),
                'fields' => array(
                    array(
                        'id' => 'user-recaptcha-public-key',
                        'type' => 'text',
                        'title' => __( 'reCAPTCHA Public Key', 'frontend_user_pro' ),
                    ),
                    array(
                        'id' => 'user-recaptcha-private-key',
                        'type' => 'text',
                        'title' => __( 'reCAPTCHA Private Key', 'frontend_user_pro' ),
                    ),
                )
            );
            do_action( 'user_settings_panel_sections', $this->sections );
        }

        /**
         * All the possible arguments for Redux.
         * For full documentation on arguments, please refer to: https://github.com/ReduxFrameworkNew/ReduxFrameworkNew/wiki/Arguments
         * */
        public function setArguments() {
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'user_settings', // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => __( 'FrontEnd User', 'frontend' ), // Name that appears at the top of your panel
                'display_version' => user_plugin_version, // Version that appears at the top of your panel
                'menu_type' => 'submenu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => false, // Show the sections below the admin menu item or not
                'menu_title' => __( 'Settings', 'frontend_user_pro' ),
                'page_title' => __( 'USER Settings', 'frontend_user_pro' ),
                'admin_bar' => false, // Show the panel pages on the admin bar
                'global_variable' => '', // Set a different name for your global variable other than the opt_name
                'dev_mode' => false, // Show the time the page took to load, etc
                'page_priority' => null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'user-about', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_options', // Permissions needed to access the options panel.
                'menu_icon' => '', // Specify a custom URL to an icon
                'last_tab' => '', // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
                'page_slug' => 'user-settings', // Page slug used to denote the panel
                'save_defaults' => true, // On load save the defaults to DB before user clicks save or not
                'default_show' => false, // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '', // What to print by the field's title if the value shown is default. Suggested: *
                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                //'domain'              => 'redux-framework-new', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
                'footer_credit'       => __( 'Thanks for using FRONTEND USER', 'frontend_user_pro' ), // Disable the footer credit of Redux. Please leave if you can help it.
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'show_import_export' => true, // REMOVE
                'system_info' => false, // REMOVE
                'help_tabs' => array(),
                'help_sidebar' => '', // __( '', $this->args['domain'] );
                'hints' => array(
                    'icon'              => 'icon-question-sign',
                    'icon_position'     => 'right',
                    'icon_color'        => 'lightgray',
                    'icon_size'         => 'normal',

                    'tip_style'         => array(
                        'color'     => 'light',
                        'shadow'    => true,
                        'rounded'   => false,
                        'style'     => '',
                    ),
                    'tip_position'      => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect' => array(
                        'show' => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'mouseover',
                        ),
                        'hide' => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );
            // Panel Intro text -> before the form
            if ( !isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
                if ( !empty( $this->args['global_variable'] ) ) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace( "-", "_", $this->args['opt_name'] );
                }
                $this->args['intro_text'] = __( 'Thanks for using FRONTEND USER', 'frontend_user_pro' );
            }
        }

    }
    global $user_save_settings;
   $user_save_settings = new USER_Settings();
}
