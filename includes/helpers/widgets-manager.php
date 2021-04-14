<?php

namespace PortunaAddon\Helpers;

defined( 'ABSPATH' ) || exit;

use \Elementor\Element_Base;

class WidgetsManager {
    use \PortunaAddon\Traits\Singleton;

    public function register_widgets() {
        $file = PORTUNA_PLUGIN_PATH . 'includes/widgets/index.php';

        if ( file_exists( $file ) ) {
            require_once( $file );
        }
    }

    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'portuna-addons-category',
            [
               'title' => __( 'Portuna Addons', 'portuna-addon' ),
               'icon'  => '',
            ]
        );
    }

    public function __construct() {
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
    }
}