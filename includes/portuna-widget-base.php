<?php

namespace PortunaAddon\Widgets;

defined( 'ABSPATH' ) || exit;

use \Elementor\Widget_Base;

abstract class Portuna_Widget_Base extends Widget_Base {
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
    }

    public function server_side_render() {
        $args          = $this->get_settings();
        $get_view_file = $this->side_render_dir() . '/view.php';

        if ( ! is_readable( $get_view_file ) ) {
            return;
        }

        ob_start();
            require( $get_view_file );
        $output = ob_get_clean();

        echo $output;
    }
}