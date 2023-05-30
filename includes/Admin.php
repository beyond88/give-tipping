<?php
namespace Give_Tipping;

/**
 * The admin class
 */
class Admin {

    /**
     * Initialize the class
     */
    function __construct() {
        new Admin\Menu();
        new Admin\Tipping();
    }

    /**
     * Dispatch and bind actions
     *
     * @return void
     */
    public function dispatch_actions( $main ) {

    }
}