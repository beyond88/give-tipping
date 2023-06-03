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
    public static $_defaultOptions = [
        'tipping_type' => 'amount',
        'give_tipping_amount' => []
    ];

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

    /**
	 * Get default price of form variable prices
	 * 
	 * @since  1.0.0
	 * @param  integer
     * @return integer
	 */
    public static function get_default_price( $form_id ) {

        $defualt_price = 0;
        $prices = apply_filters( 'give_form_variable_prices', give_get_variable_prices( $form_id ), $form_id );

        if( ! empty( $prices ) ) {
            foreach ( $prices as $price ) {
                if( array_key_exists( '_give_default', $price ) ){
                    $defualt_price = $price['_give_amount']; 
                }
            }
        }

        return $defualt_price; 
    }

    /**
	 * Get default price of form variable prices
	 * 
	 * @since  1.0.0
	 * @param  integer
     * @return integer
	 */
    public static function convert_percentage_into_amount( $total, $percentage ) {
        if( is_numeric( $total ) && is_numeric( $percentage )) {
            return ceil( ( $total * $percentage ) / 100 );
        }
    }

    /**
	 * Get default price of form variable prices
	 * 
	 * @since  1.0.0
	 * @param  none
     * @return array
	 */
    public static function get_tipping_list() {

        $form_id    = ! empty( $_GET['form_id'] ) ? absint( $_GET['form_id'] ) : null;

        global $wpdb;
        $tableName = $wpdb->prefix . 'give_donationmeta';
        $data = [];        
        $sql = "SELECT * FROM {$tableName} WHERE {$tableName}.meta_key ='_give_tip_amount'";

        if( isset( $form_id ) ) {
            $sql = "SELECT * FROM {$tableName} WHERE {$tableName}.meta_key ='_give_current_page_id' AND {$tableName}.meta_value =".$form_id."";
        }
        $data = $wpdb->get_results( $sql, ARRAY_A );
        return (array) $data;

    }

}