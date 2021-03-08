<?php

namespace PortunaAddon\Helpers;

defined( 'ABSPATH' ) || exit;

use \Elementor\Element_Base;
use \PortunaAddon\Helpers\Utils;

class ControlsManager {
    use \PortunaAddon\Traits\Singleton;

    const ELEMENTOR_AREA = 'elementor_area';
    const SELECT_IMAGE   = 'select_image';

    public function controls_register( $controls_manager ) {
        $controls_manager->register_control( self::ELEMENTOR_AREA, new \PortunaAddon\Controls\Elementor_Area() );
        $controls_manager->register_control( self::SELECT_IMAGE, new \PortunaAddon\Controls\Select_Image() );
    }

    public function controls_styles_manager() {
        wp_enqueue_style(
            'portuna-addon-controls-elementor-area-style',
            plugins_url( 'portuna-addon/assets/' ) . 'css/elementor-area.min.css',
            [],
            null
        );
    }

    public function controls_scripts_manager() {
        wp_enqueue_script(
            'portuna-addon-controls-elementor-area-script',
            plugins_url( 'portuna-addon/assets/' ) . 'js/elementor-area.min.js',
            [],
            null
        );
    }

    public function __construct() {
        // Register Controls.
        add_action( 'elementor/controls/controls_registered', [ $this, 'controls_register' ], 11 );

        // Register styles & scripts.
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'controls_styles_manager' ] );
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'controls_scripts_manager' ] );

        // Initialization controls classes.
        $elementor_area_utils = new Utils();
        $elementor_area_utils->init_view();
    }
}