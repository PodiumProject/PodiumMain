<?php
/**
 * The Redux Framework New
 *
 * A simple, truly extensible and fully responsive options framework 
 * for WordPress themes and plugins. Developed with WordPress coding
 * standards and PHP best practices in mind.
 *
 * Plugin Name:     Redux Framework New
 * Plugin URI:      http://wordpress.org/plugins/redux-framework-new
 * Github URI:      https://github.com/ReduxFrameworkNew/ReduxFrameworkNew
 * Description:     Redux is a simple, truly extensible options framework for WordPress themes and plugins.
 * Author:          Redux Team
 * Author URI:      http://reduxframework.com
 * Version:         3.1.8
 * Text Domain:     redux-framework-new
 * License:         GPL3+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:     /ReduxFrameworkNew/ReduxCore/languages
 *
 * @package         ReduxFrameworkNew
 * @author          Dovy Paukstys <dovy@reduxframework.com>
 * @author          Daniel J Griffiths <ghost1227@reduxframework.com>
 * @author          Lee Mason <lee@reduxframework.com>
 * @author          Kevin Provance <kevin@reduxframework.com>
 * @license         GNU General Public License, version 2
 * @copyright       2012-2013 Redux Framework New
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
}


// Require the main plugin class
require_once( plugin_dir_path( __FILE__ ) . 'class.redux-plugin.php' );

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'ReduxFrameworkNew', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ReduxFrameworkNew', 'deactivate' ) );

// Get plugin instance
//add_action( 'plugins_loaded', array( 'ReduxFrameworkNew', 'instance' ) );

// The above line prevents ReduxFrameworkNew from instancing until all plugins have loaded.
// While this does not matter for themes, any plugin using Redux will not load properly.
// Waiting until all plugsin have been loaded prevents the ReduxFrameworkNew class from 
// being created, and fails the !class_exists('ReduxFrameworkNew') check in the sample_config.php, 
// and thus prevents any plugin using Redux from loading their config file.
ReduxFrameworkNew::instance();
