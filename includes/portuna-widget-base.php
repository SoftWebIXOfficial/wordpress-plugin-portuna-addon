<?php

namespace PortunaAddon\Widgets;

defined( 'ABSPATH' ) || exit;

use \Elementor\Widget_Base;

abstract class Portuna_Widget_Base extends Widget_Base {

    public function __construct( $data = [], $args = null ) {

        parent::__construct( $data, $args );

        // Editor Scripts.
        add_action(
            'elementor/editor/before_enqueue_scripts',
            [ $this, 'test' ]
        );
    }

    public function test() {

        wp_enqueue_script(
            'mega-menu-script-editor',
            plugin_dir_url( dirname( __FILE__ ) ) . 'mega-menu/assets/js/editor.min.js',
            [ 'jquery' ],
            null,
            true
        );
    }

    public function server_side_render() {

        $args          = $this->get_settings_for_display();
        $get_view_file = $this->side_render_dir() . '/layout1.php';

        if ( ! is_readable( $get_view_file ) ) {
            return;
        }

        ob_start();
            require( $get_view_file );
        $output = ob_get_clean();

        echo $output;
    }
}