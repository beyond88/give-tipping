<?php

namespace Give_Tipping;
use Give_Tipping\Helpers;

/**
 * Installer class
 */
class Installer {

    /**
     * Run the installer
     *
     * @return void
     */
    public function run() {
        $this->add_version();
    }

    /**
     * Add time and version on DB
     */
    public function add_version() {
        $installed = get_option( 'give_tipping_installed' );

        if ( ! $installed ) {
            update_option( 'give_tipping_installed', time() );
        }

        update_option( 'give_tipping_version', GIVE_TIPPING_VERSION );
    }

}
