<?php

namespace PortunaAddon\Core\Cpt;

defined( 'ABSPATH' ) || exit;

class Init {
    public function __construct() {
        $this->include_files();
    }

    public static function get_dir() {
        return PORTUNA_PLUGIN_PATH . 'includes/core/elementor-cpt/';
    }

    private function include_files() {
        include_once self::get_dir() . 'cpt-register.php';
        include_once self::get_dir() . 'cpt-api.php';
    }
}

// new Init();