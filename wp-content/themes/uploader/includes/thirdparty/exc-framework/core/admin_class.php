<?php defined('ABSPATH') OR die('restricted access');

/**
 * Extracoding Framework
 *
 * An open source extracoding framework
 *
 * @package     Extracoding framework
 * @author      Extracoding team <info@extracoding.com>
 * @copyright   Copyright 2014 © Extracoding - All rights reserved
 * @license     http://extracoding.com/framework/license.html
 * @link        http://extracoding.com
 * @version     Version 1.0
 */

/**
 * eXc_Wp_Admin_Class
 *
 * Wordpress admin panel class
 *
 * @package     Extracoding framework
 * @subpackage  Core
 * @category    Core
 * @author      Hassan R. Bhutta
 * @since       1.0
 * @license Copyright 2014 © Extracoding. - All rights reserved
 *
 */

 /** Code Updated */
if ( ! class_exists( 'eXc_Admin_Class' ) )
{
    class eXc_Admin_Class
    {
        private $eXc;

        function __construct( &$_eXc )
        {
            $this->eXc = $_eXc;
        }


        public function edit( $section )
        {

            $filename = ( ! strstr( $section, '_class' ) ) ? $section . '_class' : $section;

            $path = $this->eXc->system_path( 'core/admin', $filename );

            if ( ! $class = $this->eXc->set_path( $path )->load( $filename, '', false, $this ) )
            {
                exc_die(
                    sprintf(
                        __( 'The page %s is not found', 'exc-framework' ),
                        $section
                    )
                );
            }

            $this->eXc->clear_query();

            return $class;
        }

        public function prepare_form( &$fields )
        {
            // Load form class or get instance and then prepare fields
            return $this->eXc->load( 'core/form_class' )->prepare_fields( $fields );
        }
    }
}