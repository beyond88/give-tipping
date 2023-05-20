<?php
namespace Give_Tipping;

/**
 * Assets handlers class
 */
class Assets {

    /**
     * Class constructor
     */
    function __construct() {

        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );

        if(isset($_GET['page']) && $_GET['page'] == 'give-tipping' ){
            add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_assets' ] );
        }      
        
    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {
        return [
            'give_tipping-script' => [
                'src'     => GIVE_TIPPING_ASSETS . '/js/frontend.js',
                'version' => filemtime( GIVE_TIPPING_PATH . '/assets/js/frontend.js' ),
                'deps'    => [ 'jquery' ],
            ],
        ];
    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {        
        return [
            'give_tipping-style' => [
                'src'     => GIVE_TIPPING_ASSETS . '/css/frontend.css',
                'version' => filemtime( GIVE_TIPPING_PATH . '/assets/css/frontend.css' ),
            ],

        ];
    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function register_assets() {
 
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        foreach ( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;
            $type = isset( $script['type'] ) ? $script['type'] : '';
            if( isset( $_GET['page'] ) && $_GET['page'] == 'give-tipping' ) {

            }
            wp_enqueue_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;
            $type = isset( $script['type'] ) ? $script['type'] : '';
            
            wp_enqueue_style( $handle, $style['src'], $deps, $style['version'] );
        }

        wp_localize_script( 'give_tipping-script', 'ajax', [
            'ajax_url' 					=> admin_url('admin-ajax.php'),
        ]);
    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_admin_scripts() {
        return [
            'give_tipping-admin-script' => [
                'src'     => GIVE_TIPPING_ASSETS . '/js/admin.js',
                'version' => filemtime( GIVE_TIPPING_PATH . '/assets/js/admin.js' ),
                'deps'    => [ 'jquery' ],
            ],
        ];
    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_admin_styles() {        
        return [
            'give_tipping-admin-style' => [
                'src'     => GIVE_TIPPING_ASSETS . '/css/admin.css',
                'version' => filemtime( GIVE_TIPPING_PATH . '/assets/css/admin.css' ),                
            ],
        ];
    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function register_admin_assets() {
        $scripts = $this->get_admin_scripts();
        $styles  = $this->get_admin_styles();

        foreach ( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;
            $type = isset( $script['type'] ) ? $script['type'] : '';

            wp_enqueue_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;
            $type = isset( $script['type'] ) ? $script['type'] : '';
            
            wp_enqueue_style( $handle, $style['src'], $deps, $style['version'] );
        }

        wp_localize_script( 'give_tipping-admin-script', 'give_tipping', [
            'nonce' => wp_create_nonce( 'give_tipping-admin-nonce' ),
            'confirm' => __( 'Are you sure?', 'give-tipping' ),
            'error' => __( 'Something went wrong', 'give-tipping' ),
        ] );
    }
}