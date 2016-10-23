<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Controller_Abstract' ) ) :

abstract class eXc_Controller_Abstract
{
    /**
     * Extracoding Framework Instance
     *
     * @since 1.0
     * @var object
     */
    protected $exc_product_instance_name;

    /**
     * Argments passed to this class
     *
     * @since 1.0
     * @var object
     */
    protected $arguments = array();

    /**
     * Abstract Method to Initialize Class
     *
     * @since 1.0
     * @return null
     */
    abstract protected function initialize_class();

    function __construct( &$intance, $arguments = array() )
    {
        $this->exc_product_instance_name = $intance->get_product_name();

        $this->arguments = $arguments;

        // Initialize Class
        $this->initialize_class();
    }

    public final function &exc( $clear_query_path = true )
    {
        // Automatically clear the query path
        exc_set_product_instance_name( $this->exc_product_instance_name );

        $exc_instance =& exc_get_instance();

        if ( $clear_query_path ) {
            $exc_instance->clear_query();
        }

        return $exc_instance;
    }
}

endif;