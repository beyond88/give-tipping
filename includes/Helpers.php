<?php
namespace Give_Tipping;

/**
 * Installer class
 */
class Helpers {

    /**
    * Option name
    *
    */
    public static $_optionName  = 'gt_settings';

    /**
    * Option group
    *
    */
    public static $_optionGroup = 'gt_options_group';

    /**
    * Default option
    *
    */
    public static $_defaultOptions = [];

    /**
	 * Get settings value
	 * 
	 * @since  1.0.0
	 * @param  none
     * @return void
	 */
    public static function get_settings() {
        return wp_parse_args( get_option(self::$_optionName), self::$_defaultOptions );
    }

}