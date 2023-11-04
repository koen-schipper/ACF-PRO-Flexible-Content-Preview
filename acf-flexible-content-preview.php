<?php

/**
 * Plugin Name:       ACF Flexible Content Preview
 * Description:       Extends the ACF Flexible Content Module with a preview on hover. Adds to the usability of the flexible content field.
 * Version:           1.0.0
 * Author:            Koen Schipper
 * Author URI:        https://koenschipper.com
 */

defined('ABSPATH') || exit;

if ( ! class_exists( 'FCP' ) ) {
    final class FCP {
        public function __construct() {
            // Nothing to do
        }

        public function initialize() {
            require_once('includes/settings.php');
            require_once('includes/functions.php');
        }
    }

    function fcp() {
        global $fcp;

        if (!isset($fcp)) {
            $fcp = new FCP();
            $fcp->initialize();
        }
        return $fcp;
    }

    fcp();
}