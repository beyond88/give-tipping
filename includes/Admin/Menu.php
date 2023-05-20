<?php 

namespace Give_Tipping\Admin;

class Menu {

    /**
     * Initialize the class
     */
    function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
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

}