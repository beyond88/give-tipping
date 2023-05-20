<?php 
namespace Give_Tipping\Admin;
use Give_Tipping\Helpers; 

class Menu {

    /**
    * Option name
    *
    */
    public $_optionName  = 'gt_settings';

    /**
    * Option group
    *
    */
    public $_optionGroup = 'gt_options_group';

    /**
    * Default option
    *
    */
    public $_defaultOptions = [];

    /**
     * Initialize the class
     */
    function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'plugins_loaded', [ $this, 'set_default_options' ] );
        add_action( 'admin_init', [ $this, 'menu_register_settings' ] );
    }

    /**
     * Register admin menu
     *
     * @return void
     */
    public function admin_menu() {

        $hook = add_submenu_page(
            'edit.php?post_type=give_forms',
            __( 'Tipping', 'give-tipping' ),
            __( 'Tipping', 'give-tipping' ),
            'manage_options',
            'give-tipping',
            [ $this, 'give_tipping_callback' ]
        );
    
        add_action( 'admin_head-' . $hook, [ $this, 'enqueue_assets' ] );

    }

    public function give_tipping_callback() {
        
        $template = __DIR__ . '/views/settings.php';

        if( file_exists( $template ) ) {
            $settings = Helpers::get_settings();
            require_once $template;
        } else {
            echo $error = __('Settings page missing', 'give-tipping');
        }

    }

    /**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_assets() {

    }

    /**
	 * Save the setting options		
	 * 
	 * @since  1.0.0
	 * @param  none
     * @return void
	 */
	public function menu_register_settings() {
		add_option( $this->_optionName, $this->_defaultOptions );	
		register_setting( $this->_optionGroup, $this->_optionName );
	}

    /**
	 * Apply filter with default options
	 * 
	 * @since    1.0.0
	 * @param    none
     * @return void
	 */
	public function set_default_options() {
		return apply_filters( 'dpgw_default_options', $this->_defaultOptions );
	}

}