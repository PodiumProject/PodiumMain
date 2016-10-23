<?php
/**
 * Simple System Info
 *
 * @package     Simple System Info
 * @author      Daniel J Griffiths
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


// We need to make sure plugin.php is loaded!
require_once ABSPATH . 'wp-admin/includes/plugin.php';


if( !class_exists( 'Simple_System_Info' ) ) {

    /**
     * Main System Info class
     *
     * @author      Daniel J Griffiths
     * @since       1.0.0
     */
    class Simple_System_Info {

        /**
         * Get system info
         *
         * Returns the system info for a WordPress instance
         * 
         * @access      public
         * @author      Daniel J Griffiths
         * @since       1.0.0
         * @global      $wpdb
         * @global      object $wpdb Used to query the database
         * @param       bool $show_inactive Whether or not to show inactive plugins
         * @param       string $id The ID to assign to the returned textarea (Default: system-info-box)
         * @param       string $class The class to assign to the returned textarea (Default: none)
         * @return      string $return A string containing all system info
         */
        public function get( $show_inactive = false, $id = 'system-info-box', $class = null ) {
            global $wpdb;

            if( !defined( 'SSINFO_VERSION' ) )
                define( 'SSINFO_VERSION', '1.0.0' );
            
            $return = '<textarea readonly="readonly" onclick="this.focus(); this.select()" id="' . $id . '"' . ( $class != null ? ' class="' . $class . '"' : '' ) . ' title="To copy the system info, click below and press Ctrl+C (PC) or Cmd+C (Mac).">';

            $return .= '### Begin System Info ###' . "\n\n";
            
            $return .= "\n" . '### End System Info ###';

            $return .= '</textarea>';
        
            return $return;
        }
    }
}
