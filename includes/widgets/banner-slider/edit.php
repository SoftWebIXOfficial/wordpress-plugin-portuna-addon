<?php

namespace PortunaAddon\Widgets\Edit;

defined( 'ABSPATH' ) || exit;

use \Elementor\Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Repeater;

use \PortunaAddon\Widgets\Portuna_Widget_Base;
use \PortunaAddon\Helpers\ControlsManager;

class BannerSlider extends Portuna_Widget_Base {
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $this->widgets_enqueue_styles();
    }

    public function widgets_enqueue_styles() {
        wp_enqueue_style(
            'banner-slider-style-layout1',
            plugin_dir_url( dirname( __FILE__ ) ) . 'banner-slider/assets/css/layout1.min.css',
            [],
            null
        );
    }

    /**
     * Map the widget controls.
     * Used to allow the user to customize items.
     */
    private static $css_map = [

    ];

    /**
     *
     * return a dir
     */
    public function side_render_dir() {
        return dirname( __FILE__ );
    }

    /**
     * Widget Name – The get_name() method is a simple one, you just need to
     * return a widget name that will be used in the code.
     */
    public function get_name() {
        return 'portuna_addon_banner_slider';
    }

    /**
     * Widget Title – The get_title() method, which again, is a very simple one,
     * you need to return the widget title that will be displayed as the widget label.
     */
    public function get_title() {
        return __( 'Banner Slider', 'portuna-addon' );
    }

    /**
     * Widget Icon – The get_icon() method, is an optional but recommended method,
     * it lets you set the widget icon. you can use any of the eicon or
     * font-awesome icons, simply return the class name as a string.
     */
    public function get_icon() {
        return 'sm sm-accordion';
    }

    public function get_keywords() {
        return [ 'slides', 'carousel', 'slider', 'image' ];
    }

    /**
     * Widget Categories – The get_categories method, lets you set the category
     * of the widget, return the category name as a string.
     */
    public function get_categories() {
        return [ 'portuna-addons-category' ];
    }

    /**
     * Widget Controls – The _register_controls method lets you define which
     * controls (setting fields) your widget will have.
     */
    protected function _register_controls() {
        // Initialize contents tab.
        $this->settings_content1();
        $this->settings_content2();
    }

    public function settings_content1() {
        /**
         * Start content section.
         */
        $this->start_controls_section(
            'portuna_banner_slide',
            [
                'label' => __( 'Slide Items', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'portuna_banner_slides_elementor',
                [
                    'label'       => __( 'Slide', 'portuna-addon' ),
                    'type'        => ControlsManager::ELEMENTOR_AREA,
                    'label_block' => true
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'slide_heading',
                [
                    'label'       => __( 'Title Item Slide', 'portuna-addon' ),
                    'type'        => Controls_Manager::TEXT,
                    'description' => __( 'Slide Heading', 'portuna-addon' ),
                    'label_block' => true,
                ]
            );
            
            $this->add_control(
                'slide_item',
                [
                    'label'       => __( 'Slide Items', 'portuna-addon' ),
                    'type'        => Controls_Manager::REPEATER,
                    'show_label'  => true,
                    'default'     => [
                        [
                            'slide_heading' => __( 'Slide Heading 1', 'portuna-addon' ),
                        ],
                        [
                            'slide_heading' => __( 'Slide Heading 2', 'portuna-addon' ),
                        ],
                        [
                            'slide_heading' => __( 'Slide Heading 3', 'portuna-addon' ),
                        ],
                    ],
                    'fields'      => $repeater->get_controls(),
                    'title_field' => '{{{ slide_heading }}}',
                ]
            );

        $this->end_controls_section();
    }

    public function settings_content2() {

        $this->start_controls_section(
            'portuna_banner_settings',
            [
                'label' => __( 'Slider Settings', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'slide_nav',
                [
                    'label'              => __( 'Navigation', 'portuna-addon' ),
                    'type'               => Controls_Manager::SELECT,
                    'default'            => 'both',
                    'options'            => [
                        'both'    => __( 'Arrows & Dots', 'portuna-addon' ),
                        'arrows'  => __( 'Arrows', 'portuna-addon' ),
                        'dots'    => __( 'Dots', 'portuna-addon' ),
                        'none'    => __( 'None', 'portuna-addon' ),
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_loop',
                [
                    'label'        => __( 'Loop', 'portuna-addon' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => 'yes',
                    'return_value' => 'yes',
                ]
            );

        $this->end_controls_section();

    }

    /**
     * Render Frontend Output – The render() method, which is where you actually
     * render the code and generate the final HTML on the frontend using PHP.
     */
    protected function render() {
        $this->server_side_render();
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new BannerSlider() );