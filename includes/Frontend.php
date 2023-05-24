<?php
namespace Give_Tipping;
use Give_Tipping\Frontend\Storefront; 

/**
 * Installer class
 */
class Frontend {

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
    public function __construct() {

        new Storefront();

    }

}