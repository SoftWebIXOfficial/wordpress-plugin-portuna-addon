<?php
/**
 * The Mega Menu Functionality.
*/

namespace PortunaAddon\Modules;

defined( 'ABSPATH' ) || exit;

class Mega_Menu_Nav_Walker extends Walker_Nav_Menu {
    use \PortunaAddon\Traits\Singleton;

    private $settings = [];

    public function __construct( $settings ) {
        $this->settings = $settings;
    }
}