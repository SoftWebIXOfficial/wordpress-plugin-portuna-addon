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
        'swiper_arrows'       => ' .swiper-button-prev::after, .swiper-button-next::after',
        'swiper_arrows_hover' => ' .swiper-button-prev:hover::after, .swiper-button-next:hover::after',
        'swiper_arrow_left'   => ' .swiper-button-prev',
        'swiper_arrow_right'  => ' .swiper-button-next',
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
                'label' => __( 'Slider Items', 'portuna-addon' ),
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
                        'both'           => __( 'Arrows & Dots', 'portuna-addon' ),
                        'arrows'         => __( 'Arrows', 'portuna-addon' ),
                        'dots'           => __( 'Dots', 'portuna-addon' ),
                        'none'           => __( 'None', 'portuna-addon' ),
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_arrows_heading',
                [
                    'label'     => __( 'Arrows', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'condition' => [
                        'slide_nav'      => [ 'both', 'arrows' ],
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'slide_arrows_type',
                [
                    'label'              => __( 'Arrows Type', 'portuna-addon' ),
                    'type'               => Controls_Manager::SELECT,
                    'default'            => 'arrows',
                    'options'            => [
                        'arrows'        => __( 'Arrows', 'portuna-addon' ),
                        'custom-pro'    => __( 'Arrows Custom (Pro)', 'portuna-addon' ),
                    ],
                    'frontend_available' => true,
                    'condition'          => [
                        'slide_nav'      => [ 'both', 'arrows' ],
                    ]
                ]
            );

            $this->add_control(
                'slide_arrow_prev_icons_pro',
                [
                    'label'            => __( 'Arrow Icon (Prev)', 'portuna-addon' ),
                    'type'             => Controls_Manager::ICONS,
                    'fa4compatibility' => 'slide_arrow_prev_icon_pro',
                    'default'	  	   => [
                        'value'	  => '',
                    ],
                    'condition'        => [
                        'slide_nav'         => [ 'both', 'arrows' ],
                        'slide_arrows_type' => 'custom-pro',
                    ],
                ]
            );

            $this->add_control(
                'slide_arrow_next_icons_pro',
                [
                    'label'            => __( 'Arrow Icon (Next)', 'portuna-addon' ),
                    'type'             => Controls_Manager::ICONS,
                    'fa4compatibility' => 'slide_arrow_next_icon_pro',
                    'default'	  	   => [
                        'value'	  => '',
                    ],
                    'condition'        => [
                        'slide_nav'         => [ 'both', 'arrows' ],
                        'slide_arrows_type' => 'custom-pro',
                    ],
                ]
            );

            $this->add_responsive_control(
                'slide_arrows_size',
                [
                    'label'      => __( 'Arrow Size', 'portuna-addon' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'em', 'rem' ],
                    'range'  => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        '%' => [
                            'max' => 100,
                            'step' => 1,
                        ],
                        'em' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        'rem' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default'   => [
                        'unit'  => 'px',
                        'size'  => 15,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrows' ] => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'slide_nav'      => [ 'both', 'arrows' ],
                    ]
                ]
            );

            $this->add_control(
                'slide_arrows_distance_pro',
                [
                    'label'     => __( 'Arrows Between Distance', 'portuna-addon' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => [
                        'together'     => __( 'Together', 'portuna-addon' ),
                        'separately'   => __( 'Separately', 'portuna-addon' ),
                    ],
                    'default'   => 'together',
                    'condition' => [
                        'slide_nav'         => [ 'both', 'arrows' ],
                        'slide_arrows_type' => 'custom-pro',
                    ],
                ]
            );

            $this->add_control(
                'slide_arrows_space_pro',
                [
                    'label'     => __( 'Arrows Space', 'portuna-addon' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'em', 'rem' ],
                    'range'  => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        '%' => [
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'default'   => [
                        'unit'  => 'px',
                        'size'  => 15,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrow_left' ] => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrow_right' ] => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'slide_nav'         => [ 'both', 'arrows' ],
                        'slide_arrows_type' => 'custom-pro',
                    ],
                ]
            );

            $this->add_responsive_control(
                'slide_arrows_distance',
                [
                    'label'  => __( 'Arrows Distance', 'portuna-addon' ),
                    'type'   => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range'  => [
                        'px' => [
                            'min' => -50,
                            'max' => 500,
                        ],
                        '%' => [
                            'max'  => 100,
                            'step' => 1,
                        ],
                    ],
                    'default'   => [
                        'unit'  => 'px',
                        'size'  => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrow_left' ] => 'left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrow_right' ] => 'right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'slide_nav'         => [ 'both', 'arrows' ],
                        'slide_arrows_type' => 'arrows'
                    ]
                ]
            );

            $this->add_responsive_control(
                'slide_arrows_position_hr_orientation',
                [
                    'label'   => __( 'Arrows Horizontal Orientation', 'portuna-addon' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'toggle'  => false,
                    'default' => 'left',
                    'options' => [
                        'left'    => [
                            'title' => __( 'Left', 'portuna-addon' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'portuna-addon' ),
                            'icon' => 'eicon-h-align-right',
                        ]
                    ],
                    'condition' => [
                        'slide_nav'         => [ 'both', 'arrows' ],
                        'slide_arrows_type' => 'custom-pro'
                    ],
                    'separator' => 'before'
                ]
            );
            
            $this->add_responsive_control(
                'slide_arrows_position_hr_offset',
                [
                    'label'  => __( 'Offset (Horizontal)', 'portuna-addon' ),
                    'type'   => Controls_Manager::SLIDER,
                    'range'  => [
                        'px' => [
                            'min' => -1000,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -200,
                            'max' => 200,
                        ],
                        'vw' => [
                            'min' => -200,
                            'max' => 200,
                        ],
                        'vh' => [
                            'min' => -200,
                            'max' => 200,
                        ],
                    ],
                    'default'    => [
                        'size' => '0',
                    ],
                    'size_units' => [ 'px', '%', 'vw', 'vh' ],
                    'selectors'  => [
                        'body:not(.rtl) {{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}}',
                        'body.rtl {{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'slide_nav'         => [ 'both', 'arrows' ],
                        'slide_arrows_type' => 'custom-pro'
                    ]
                ]
            );

            $this->add_responsive_control(
                'slide_arrows_position_vr_orientation',
                [
                    'label'   => __( 'Arrows Vertical Orientation', 'portuna-addon' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'toggle'  => false,
                    'default' => 'top',
                    'options' => [
                        'top'    => [
                            'title' => __( 'Top', 'portuna-addon' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'portuna-addon' ),
                            'icon' => 'eicon-v-align-bottom',
                        ]
                    ],
                    'condition' => [
                        'slide_nav'         => [ 'both', 'arrows' ],
                        'slide_arrows_type' => 'custom-pro'
                    ]
                ]
            );

            $this->add_responsive_control(
                'slide_arrows_position_vr_offset',
                [
                    'label'  => __( 'Offset (Vertical)', 'portuna-addon' ),
                    'type'   => Controls_Manager::SLIDER,
                    'range'  => [
                        'px' => [
                            'min' => -1000,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -200,
                            'max' => 200,
                        ],
                        'vw' => [
                            'min' => -200,
                            'max' => 200,
                        ],
                        'vh' => [
                            'min' => -200,
                            'max' => 200,
                        ],
                    ],
                    'default'    => [
                        'size' => '0',
                    ],
                    'size_units' => [ 'px', '%', 'vw', 'vh' ],
                    'selectors'  => [
                        'body:not(.rtl) {{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}}',
                        'body.rtl {{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'slide_nav'         => [ 'both', 'arrows' ],
                        'slide_arrows_type' => 'custom-pro'
                    ]
                ]
            );

            $this->start_controls_tabs( 'arrows_effect' );

                $this->start_controls_tab(
                    'slide_arrows_normal',
                    [
                        'label'     => __( 'Normal', 'portuna-addon' ),
                        'condition' => [
                            'slide_nav'  => [ 'both', 'arrows' ],
                        ]
                    ]
                );

                    $this->add_control(
                        'slide_arrows_color',
                        [
                            'label'     => __( 'Arrows Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_arrows' ] => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'slide_nav'  => [ 'both', 'arrows' ],
                            ]
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'slide_arrows_hover',
                    [
                        'label'     => __( 'Hover', 'portuna-addon' ),
                        'condition' => [
                            'slide_nav'  => [ 'both', 'arrows' ],
                        ]
                    ]
                );

                    $this->add_control(
                        'slide_arrows_color_hover',
                        [
                            'label'     => __( 'Arrows Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_arrows_hover' ] => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'slide_nav'  => [ 'both', 'arrows' ],
                            ]
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'slide_pagination_heading',
                [
                    'label'     => __( 'Pagination', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'condition' => [
                        'slide_nav'      => [ 'both', 'dots' ],
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'slide_pagination_type',
                [
                    'label'              => __( 'Pagination Type', 'portuna-addon' ),
                    'type'               => Controls_Manager::SELECT,
                    'default'            => 'bullets',
                    'options'            => [
                        'bullets'        => __( 'Bullets', 'portuna-addon' ),
                        'fraction'       => __( 'Fraction', 'portuna-addon' ),
                        'progressbar'    => __( 'Progress Bar', 'portuna-addon' ),
                        'custom-pro'     => __( 'Custom (Pro)', 'portuna-addon' ),
                    ],
                    'frontend_available' => true,
                    'condition'          => [
                        'slide_nav'      => [ 'both', 'dots' ],
                    ]
                ]
            );

            $this->add_control(
                'slide_center',
                [
                    'label'              => __( 'Slide Center', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => 'no',
                    'return_value'       => 'yes',
                    'frontend_available' => true,
                    'separator'          => 'before',
                ]
            );

            $this->add_control(
                'slide_preview',
                [
                    'label'              => __( 'Slides Preview', 'portuna-addon' ),
                    'type'               => Controls_Manager::NUMBER,
                    'default'            => 1,
                    'min'                => 1,
                    'required'           => true,
                    'description'        => __( 'Set your number of slides preview (slides visible at the same time on slider\'s container).', 'portuna-addon' ),
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_space',
                [
                    'label'              => __( 'Space Between', 'portuna-addon' ),
                    'type'               => Controls_Manager::NUMBER,
                    'default'            => 0,
                    'min'                => 0,
                    'required'           => true,
                    'description'        => __( 'Distance between slides in px.', 'portuna-addon' ),
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_autoplay',
                [
                    'label'              => __( 'Autoplay', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => 'yes',
                    'return_value'       => 'yes',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_pause_delay',
                [
                    'label'              => __( 'Autoplay Delay', 'portuna-addon' ),
                    'type'               => Controls_Manager::NUMBER,
                    'default'            => 5000,
                    'min'                => 1000,
                    'required'           => true,
                    'description'        => __( 'Set delay (in millisecond).', 'portuna-addon' ),
                    'condition'          => [
                        'slide_autoplay' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_pause_interaction',
                [
                    'label'              => __( 'Pause on Interaction', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => 'no',
                    'return_value'       => 'yes',
                    'condition'          => [
                        'slide_autoplay' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_pause_hover',
                [
                    'label'              => __( 'Pause on Mouse Hover', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => 'no',
                    'return_value'       => 'yes',
                    'condition'          => [
                        'slide_autoplay' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_loop',
                [
                    'label'              => __( 'Loop', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => 'no',
                    'return_value'       => 'yes',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_simulate_touch',
                [
                    'label'              => __( 'Simulate Touch', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => 'no',
                    'return_value'       => 'yes',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_effect',
                [
                    'label'              => __( 'Effect Type', 'portuna-addon' ),
                    'type'               => Controls_Manager::SELECT,
                    'default'            => 'slide',
                    'options'            => [
                        'slide'          => __( 'Slide', 'portuna-addon' ),
                        'fade'           => __( 'Fade', 'portuna-addon' ),
                        'cube'           => __( 'Cube', 'portuna-addon' ),
                        'coverflow'      => __( 'Coverflow', 'portuna-addon' ),
                        'flip'           => __( 'Flip', 'portuna-addon' ),
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_effect_speed',
                [
                    'label'              => __( 'Effect Speed', 'portuna-addon' ),
                    'type'               => Controls_Manager::NUMBER,
                    'default'            => 1000,
                    'min'                => 100,
                    'required'           => true,
                    'frontend_available' => true,
                    'description'        => __( 'Set speed (in millisecond).', 'portuna-addon' ),
                ]
            );

            $this->add_control(
                'slide_direction',
                [
                    'label'   => __( 'Direction', 'portuna-addon' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'toggle'  => false,
                    'default' => 'horizontal',
                    'options' => [
                        'horizontal'    => [
                            'title' => __( 'Horizontal', 'portuna-addon' ),
                            'icon' => 'eicon-h-align-stretch',
                        ],
                        'vertical' => [
                            'title' => __( 'Vertical', 'portuna-addon' ),
                            'icon' => 'eicon-v-align-stretch',
                        ]
                    ],
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