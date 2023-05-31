<?php
/**
 * Plugin Name: Give - Tipping
 * Plugin URI: https://github.com/beyond88/give-tipping
 * Description: This plugin enables the platform to receive and process tips from donors.
 * Author: Mohiuddin Abdul Kader
 * Author URI: https://github.com/beyond88/give-tipping/
 * Version: 1.0.0
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Text Domain: give-tipping
 * Domain Path: /languages
 * @package give-tipping
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require(dirname(__FILE__) . '/wp-load.php');

use Give_Tipping\ServiceProvider;

/**
 * The main plugin class
 */
final class Give_Tipping {

    /**
    * Plugin version
    *
    * @var string
    */
    const version = '1.0.0';

    /**
     * @since 1.9.8 add MigrationsServiceProvider
     * @since 1.9.0
     *
     * @var string[]
     */
    public $service_providers = [
        ServiceProvider::class
    ];

    /**
     * Class constructor
     */
    private function __construct() {

        //REMOVE THIS AFTER DEV
        error_reporting(E_ALL ^ E_DEPRECATED);

        $this->define_constants();

        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        if ( is_plugin_active( 'give/give.php' ) ) {
            register_activation_hook( GIVE_TIPPING_FILE, [ $this, 'activate' ] );
            add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );

        } else {
            add_action( 'admin_notices', [ $this, 'givewp_plugin_required' ] );
        }
    }

    public function givewp_plugin_required()
    {
        ?>
        <script>
            (function($) {
                'use strict';
                $(document).on("click", '.notice-dismiss', function(){
                    $(this).parent().fadeOut();
                });
            })(jQuery);
        </script>
        <div id="message" class="error notice is-dismissible">
            <p><?php echo __('GiveWP plugin is required for GIVE_TIPPING!', 'give-kindness'); ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php echo __('Dismiss this notice.', 'give-kindness'); ?></span>
            </button>
        </div>
        <?php
    }

    /**
     * Initializes a singleton instance
     *
     * @return \Give_Tipping
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
            $instance->setup();
        }

        return $instance;
    }

    /**
     * Setup Fee Recovery.
     *
     * @since  1.3.0
     * @access private
     */
    private function setup()
    {

        add_action('before_give_init', [$this, 'register_service_providers']);

    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'GIVE_TIPPING_VERSION', self::version );
        define( 'GIVE_TIPPING_FILE', __FILE__ );
        define( 'GIVE_TIPPING_PATH', __DIR__ );
        define( 'GIVE_TIPPING_TEMPLATES', GIVE_TIPPING_PATH . '/includes/Templates/' );
        define( 'GIVE_TIPPING_URL', plugins_url( '', GIVE_TIPPING_FILE ) );
        define( 'GIVE_TIPPING_ASSETS', GIVE_TIPPING_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        new Give_Tipping\Assets();

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            new Give_Tipping\Ajax();
        }

        if ( is_admin() ) {
            new Give_Tipping\Admin();
        } else {
            new Give_Tipping\Frontend();
        }

        new Give_Tipping\API();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installer = new Give_Tipping\Installer();
        $installer->run();
    }

    /**
     * Registers the Service Providers with GiveWP core
     *
     * @since 1.9.0
     */
    public function register_service_providers()
    {
        foreach ($this->service_providers as $service_provider) {
            give()->registerServiceProvider($service_provider);
        }
    }
}

/**
 * Initializes the main plugin
 */
function Give_Tipping() {
    return Give_Tipping::init();
}

// kick-off the plugin
Give_Tipping();