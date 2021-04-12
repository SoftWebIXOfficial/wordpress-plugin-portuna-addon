<?php

namespace PortunaAddon\Widgets\Edit;

defined( 'ABSPATH' ) || exit;

use \Elementor\Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Repeater;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Background;

use \PortunaAddon\Widgets\Portuna_Widget_Base;
use \PortunaAddon\Helpers\ControlsManager;

class MegaMenu extends Portuna_Widget_Base {
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'mega-menu-style-layout1',
            plugin_dir_url( dirname( __FILE__ ) ) . 'mega-menu/assets/css/layout1.min.css',
            [],
            null
        );
        wp_register_script(
            'mega-menu-script-layout1',
            plugin_dir_url( dirname( __FILE__ ) ) . 'mega-menu/assets/js/layout1.min.js',
            [],
            null
        );
    }

    public function get_style_depends() {
        return [ 'mega-menu-style-layout1' ];
    }

    public function get_script_depends() {
    	return [ 'jquery', 'mega-menu-script-layout1' ];
    }

    /**
     * Map the widget controls.
     * Used to allow the user to customize items.
     */
    private static $css_map = [
        'wrap_menu'                                 => ' .portuna-addon-menu-items:not(.portuna-addon-sub-menu)',
        'wrap_menu_horizontal'                      => '.portuna-addon-menu-horizontal .portuna-addon-menu-items:not(.portuna-addon-sub-menu)',
        'wrap_menu_vertical'                        => '.portuna-addon-menu-vertical .portuna-addon-menu-items:not(.portuna-addon-sub-menu)',
        'wrap_submenu_container'                    => ' .portuna-addon-sub-menu',
        'wrap_submenu_content'                      => ' div.portuna-addon-sub-menu-content',
        'wrap_submegamenu_container'                => ' ul.portuna-addon-sub-mega-menu',
        'wrap_menu_padding'                         => ' .portuna-addon--mega-menu--content > .portuna-addon-menu-items',
        'wrap_menu_link'                            => ' .portuna-addon-menu__wrapper-content-link > a',
        'wrap_menu_item_link'                       => ' .portuna-addon-top-menu .portuna-addon-menu-item .first-level-link, .portuna-addon-sub-menu .portuna-addon-menu-item .nested-level-link',
        'wrap_menu_item_link_hover'                 => ' .portuna-addon-top-menu .portuna-addon-menu-item:hover .first-level-link, .portuna-addon-sub-menu .portuna-addon-menu-item:hover .nested-level-link',
        'wrap_submenu'                              => ' .portuna-addon-sub-menu',
        'wrap_submenu_indicator'                    => ' ',
        'wrap_menu_icon'                            => ' ',
        'wrap_submenu_icon'                         => ' ',
    ];

    /**
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
        return 'portuna-addon-mega-menu';
    }

    /**
     * Widget Title – The get_title() method, which again, is a very simple one,
     * you need to return the widget title that will be displayed as the widget label.
     */
    public function get_title() {
        return __( 'Mega Menu', 'portuna-addon' );
    }

    /**
     * Widget Icon – The get_icon() method, is an optional but recommended method,
     * it lets you set the widget icon. you can use any of the eicon or
     * font-awesome icons, simply return the class name as a string.
     */
    public function get_icon() {
        return 'mdi mdi-form-dropdown';
    }

    public function get_keywords() {
        return [ 'header', 'mega-menu', 'nav', 'menu', 'submenu', 'link', 'horizontal', 'vertical' ];
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
        $this->settings_section1();

        $this->style_section1();
        $this->style_section2();
        $this->style_section3();
        $this->style_section4();
        $this->style_section5();
        $this->style_section6();
        $this->style_section7();
    }

    public function settings_section1() {
        $this->start_controls_section(
            'section_content_menu',
            [
                'label' => __( 'Menu Settings', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'menu_list',
                [
                    'label'       => __( 'Choose Menus', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '',
                    'options'     => $this->get_available_menus(),
                    'description' => sprintf( __( '<a href="%s" target="_blank">Manage Menus</a>.', 'portuna-addon' ), admin_url( 'nav-menus.php' ) ),
                ]
            );

            $this->add_control(
                'menu_type_orientation',
                [
                    'label'              => __( 'Menu Type Orientation', 'portuna-addon' ),
                    'type'               => Controls_Manager::SELECT,
                    'default'            => 'horizontal',
                    'options'            => [
                        'horizontal'     => __( 'Horizontal', 'portuna-addon' ),
                        'vertical'       => __( 'Vertical', 'portuna-addon' ),
                        'dropdown'       => __( 'Dropdown', 'portuna-addon' ),
                    ],
                    'prefix_class'       => 'portuna-addon-menu-',
                    'style_transfer'     => true,
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'menu_type_animation_hr',
                [
                    'label'       => __( 'Menu Animation', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'fade',
                    'options'     => [
                        'fade'        => __( 'Fade', 'portuna-addon' ),
                        'fade-up'     => __( 'Fade Up', 'portuna-addon' ),
                        'fade-down'   => __( 'Fade Down', 'portuna-addon' ),
                    ],
                    'condition'   => [
                        'menu_type_orientation' => 'horizontal'
                    ],
                ]
            );

            $this->add_control(
                'menu_type_side_position',
                [
                    'label'       => __( 'Sub Menu Side Position', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'right-side',
                    'options'     => [
                        'left-side'   => __( 'Left Side', 'portuna-addon' ),
                        'right-side'  => __( 'Right Side', 'portuna-addon' ),
                    ],
                    'condition'   => [
                        'menu_type_orientation' => 'vertical'
                    ],
                ]
            );

        $this->end_controls_section();
    }

    // General Option
    public function style_section1() {
        $this->start_controls_section(
            'section_general_style',
            [
                'label' => __( 'General Options', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'general_menu_width_hr',
                [
                    'label'    => __( 'Menu Width', 'portuna-addon' ),
                    'type'     => Controls_Manager::SLIDER,
                    'range'    => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default'   => [
                        'size'  => 100,
                        'unit'  => '%'
                    ],
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_horizontal' ] => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'menu_type_orientation' => 'horizontal'
                    ]
                ]
            );

            $this->add_responsive_control(
                'general_menu_width_vr',
                [
                    'label'    => __( 'Menu Width', 'portuna-addon' ),
                    'type'     => Controls_Manager::SLIDER,
                    'range'    => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default'   => [
                        'size'  => 250,
                        'unit'  => 'px'
                    ],
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_vertical' ] => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'menu_type_orientation' => 'vertical'
                    ]
                ]
            );

            $this->add_responsive_control(
                'general_menu_alignment_hr',
                [
                    'label'     => __( 'Menu Alignment', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'toggle'    => false,
                    'options'   => [
                        'flex-start'     => [
                            'title'  => __( 'Left', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-left',
                        ],
                        'center'         => [
                            'title'  => __( 'Center', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-center',
                        ],
                        'flex-end'       => [
                            'title'  => __( 'Right', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-right',
                        ],
                        'space-between'  => [
                            'title'  => __( 'Right', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-stretch',
                        ],
                    ],
                    'default'   => 'center',
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_horizontal' ] => 'justify-content: {{VALUE}}',
                    ],
                    'condition' => [
                        'menu_type_orientation' => 'horizontal'
                    ]
                ]
            );


            $this->add_responsive_control(
                'general_menu_alignment_vr',
                [
                    'label'     => __( 'Menu Alignment', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'toggle'    => false,
                    'options'   => [
                        'left'     => [
                            'title'  => __( 'Left', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-left',
                        ],
                        'center'         => [
                            'title'  => __( 'Center', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-center',
                        ],
                        'right'       => [
                            'title'  => __( 'Right', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-right',
                        ]
                    ],
                    'default'              => 'center',
                    'selectors_dictionary' => [
                        'left'   => 'margin-right: auto;',
                        'center' => 'margin-left: auto; margin-right: auto;',
                        'right'  => 'margin-left: auto;',
                    ],
                    'selectors'            => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_vertical' ] => '{{VALUE}}',
                    ],
                    'condition'            => [
                        'menu_type_orientation' => 'vertical'
                    ]
                ]
            );

            $this->add_responsive_control(
                'general_menu_padding',
                [
                    'label'      => __( 'Menu Padding', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default'    => [
                        'top'    => '7',
                        'right'  => '32',
                        'bottom' => '7',
                        'left'   => '32',
                        'unit'   => 'px'
                    ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_padding' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_control(
                'general_menu_background',
                [
                    'label'     => __( 'Menu Background Color', 'portuna-addon' ),
                    'type'      => Controls_Manager::COLOR,
                    //'default'   => '#212529',
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ] => 'background-color: {{VALUE}};'
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'label'    => __( 'Menu Box Shadow', 'portuna-addon' ),
                    'name'     => 'general_menu_box_shadow',
                    'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ],
                ]
            );

        $this->end_controls_section();
    }

    // Menu
    public function style_section2() {
        $this->start_controls_section(
            'section_menu_item_style',
            [
                'label' => __( 'Menu', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'menu_item_pointer',
                [
                    'label'       => __( 'Pointer (Hover)', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'none',
                    'options'     => [
                        'none'       => __( 'None', 'portuna-addon' ),
                        'underline'  => __( 'Underline', 'portuna-addon' ),
                        'background' => __( 'Background', 'portuna-addon' ),
                    ],
                ]
            );

            $this->add_control(
                'menu_item_pointer_animation_line',
                [
                    'label'       => __( 'Pointer Animation', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'none',
                    'options'     => [
                        'none'       => __( 'None', 'portuna-addon' ),
                        'fade'       => __( 'Fade', 'portuna-addon' ),
                        'slide'      => __( 'Slide', 'portuna-addon' ),
                        'grow'       => __( 'Grow', 'portuna-addon' ),
                        'drop-in'    => __( 'Drop In', 'portuna-addon' ),
                        'drop-out'    => __( 'Drop Out', 'portuna-addon' ),
                    ],
                    'condition' => [
                        'menu_item_pointer' => [ 'underline' ],
                    ],
                ]
            );

            $this->add_control(
                'menu_item_pointer_animation_background',
                [
                    'label'       => __( 'Pointer Animation', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'none',
                    'options'     => [
                        'none'                   => __( 'None', 'portuna-addon' ),
                        'fade'                   => __( 'Fade', 'portuna-addon' ),
                        'grow'                   => __( 'Grow', 'portuna-addon' ),
                        'shrink'                 => __( 'Shrink', 'portuna-addon' ),
                        'sweep-left'             => __( 'Sweep Left', 'portuna-addon' ),
                        'sweep-right'            => __( 'Sweep Right', 'portuna-addon' ),
                        'sweep-up'               => __( 'Seep Up', 'portuna-addon' ),
                        'sweep-down'             => __( 'Seep Down', 'portuna-addon' ),
                        'shutter-in-vertical'    => __( 'Seep Down', 'portuna-addon' ),
                        'shutter-out-vertical'   => __( 'Seep Down', 'portuna-addon' ),
                        'shutter-in-horizontal'  => __( 'Seep Down', 'portuna-addon' ),
                        'shutter-out-horizontal' => __( 'Seep Down', 'portuna-addon' ),
                    ],
                    'condition' => [
                        'menu_item_pointer' => [ 'background' ],
                    ],
                ]
            );

            $this->add_responsive_control(
                'menu_item_padding',
                [
                    'label'      => __( 'Padding', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_responsive_control(
                'menu_item_margin',
                [
                    'label'      => __( 'Margin', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_control(
                'menu_heading_items',
                [
                    'label'     => __( 'Menu Items', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label'           => __( 'Item Typography', 'portuna-addon' ),
                    'name'            => 'menu_item_typo',
                    //'selector'        => '{{WRAPPER}}' . self::$css_map[ '' ],
                    'fields_options'  => [
                        'font_weight' => [
                            'default' => '400'
                        ]
                    ]
                ]
            );

            $this->start_controls_tabs( 'menu_effects' );

                $this->start_controls_tab(
                    'menu_item_normal',
                    [
                        'label' => __( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'menu_item_color',
                        [
                            'label'     => __( 'Text Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'menu_item_bgcolor',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'menu_item_hover',
                    [
                        'label' => __( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'menu_item_color_hover',
                        [
                            'label'     => __( 'Text Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link_hover' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'menu_item_bgcolor_hover',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'menu_item_pointer!' => 'background'
                            ]
                        ]
                    );

                    $this->add_control(
                        'menu_item_pointer_color_hover',
                        [
                            'label'     => __( 'Pointer Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link_hover' ] => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'menu_item_pointer!' => 'none'
                            ]
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    // Sub Menu
    public function style_section3() {
        $this->start_controls_section(
            'section_submenu_item_style',
            [
                'label' => __( 'Sub Menu', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'submenu_group' );

                $this->start_controls_tab(
                    'submenu_normal_panel',
                    [
                        'label' => __( 'Normal Panel', 'portuna-addon' ),
                    ]
                );

                    $this->add_responsive_control(
                        'submenu_normal_panel_width_hr',
                        [
                            'label'    => __( 'Width', 'portuna-addon' ),
                            'type'     => Controls_Manager::SLIDER,
                            'range'    => [
                                'px' => [
                                    'min' => 100,
                                    'max' => 1000,
                                ],
                                '%' => [
                                    'min' => 10,
                                    'max' => 100,
                                ],
                            ],
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_submenu_container' ] => 'width: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_submenu_content' ] => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'menu_type_orientation' => 'horizontal'
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_normal_panel_width_vr',
                        [
                            'label'    => __( 'Width', 'portuna-addon' ),
                            'type'     => Controls_Manager::SLIDER,
                            'range'    => [
                                'px' => [
                                    'min' => 100,
                                    'max' => 1000,
                                ],
                                '%' => [
                                    'min' => 10,
                                    'max' => 100,
                                ],
                            ],
                            'default'    => [
                                'size'   => 200,
                                'unit'   => 'px'
                            ],
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_submenu_container' ] => 'width: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_submenu_content' ] => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'menu_type_orientation' => 'vertical'
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'label'    => __( 'Sub Menu Background', 'portuna-addon' ),
                            'name'     => 'submenu_background_panel',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'label'    => __( 'Sub Menu Box Shadow', 'portuna-addon' ),
                            'name'     => 'submenu_shadow_panel',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ],
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_padding_panel',
                        [
                            'label'      => __( 'Padding Container', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_margin_panel',
                        [
                            'label'      => __( 'Margin Container', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );

                    $this->add_control(
                        'submenu_border_panel',
                        [
                            'label'     => __( 'Border Type', 'portuna-addon' ),
                            'type'      => Controls_Manager::SELECT,
                            'options'   => [
                                'none'   => __( 'None', 'portuna-addon' ),
                                'solid'  => __( 'Solid', 'portuna-addon' ),
                                'double' => __( 'Double', 'portuna-addon' ),
                                'dotted' => __( 'Dotted', 'portuna-addon' ),
                                'dashed' => __( 'Dashed', 'portuna-addon' ),
                                'groove' => __( 'Groove', 'portuna-addon' ),
                            ],
                            'default'   => 'none',
                            //'selectors' => [
                                //'{{SELECTOR}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-style: {{VALUE}};',
                            //],
                        ]
                    );

                    $this->add_control(
                        'submenu_border_color_panel',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'submenu_border_panel!' => 'none',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_border_width_panel',
                        [
                            'label'      => esc_html__( 'Border Width', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'default'    => [
                                'top'    => '1',
                                'right'  => '1',
                                'bottom' => '1',
                                'left'   => '1'
                            ],
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            //],
                            'condition' => [
                                'submenu_border_panel!' => 'none',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_radius_panel',
                        [
                            'label'      => esc_html__( 'Border Radius', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            //],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'submenu_mega_panel',
                    [
                        'label' => __( 'Mega Panel', 'portuna-addon' ),
                    ]
                );

                    $this->add_responsive_control(
                        'mega_panel_width_hr',
                        [
                            'label'    => __( 'Width', 'portuna-addon' ),
                            'type'     => Controls_Manager::SLIDER,
                            'range'    => [
                                'px' => [
                                    'min' => 100,
                                    'max' => 1100,
                                ],
                                '%' => [
                                    'min' => 10,
                                    'max' => 100,
                                ],
                            ],
                            'size_units' => [ 'px', '%' ],
                            'default'    => [
                                'size'  => 100,
                                'unit'  => '%'
                            ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_submegamenu_container' ] => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'menu_type_orientation' => 'horizontal'
                            ]
                        ]
                    );

                    $this->add_responsive_control(
                        'mega_panel_width_vr',
                        [
                            'label'    => __( 'Width', 'portuna-addon' ),
                            'type'     => Controls_Manager::SLIDER,
                            'range'    => [
                                'px' => [
                                    'min' => 100,
                                    'max' => 1100,
                                ],
                                '%' => [
                                    'min' => 10,
                                    'max' => 100,
                                ],
                            ],
                            'size_units' => [ 'px', '%' ],
                            'default'    => [
                                'size'  => 500,
                                'unit'  => 'px'
                            ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_submegamenu_container' ] => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'menu_type_orientation' => 'vertical'
                            ]
                        ]
                    );

                    $this->add_responsive_control(
                        'mega_panel_is_active',
                        [
                            'label'      => esc_html__( 'Mega Menu is Active?', 'portuna-addon' ),
                            'type'       => Controls_Manager::HIDDEN,
                            'default'    => 'yes',
                            'options'    => [
                                'yes'   => __( 'yes', 'portuna-addon' ),
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'label'           => __( 'Badge Typography', 'portuna-addon' ),
                            'name'            => 'icon_nested_typo',
                            //'selector'        => '{{WRAPPER}}' . self::$css_map[ '' ],
                            'fields_options'  => [
                                'font_weight' => [
                                    'default' => '400'
                                ]
                            ]
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'submenu_heading_items',
                [
                    'label'     => __( 'Sub Menu Items', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label'           => __( 'Item Typography', 'portuna-addon' ),
                    'name'            => 'submenu_item_typo',
                    //'selector'        => '{{WRAPPER}}' . self::$css_map[ '' ],
                    'fields_options'  => [
                        'font_weight' => [
                            'default' => '400'
                        ]
                    ]
                ]
            );

            $this->add_control(
                'submenu_item_pointer',
                [
                    'label'       => __( 'Pointer (Hover)', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'none',
                    'options'     => [
                        'none'       => __( 'None', 'portuna-addon' ),
                        'underline'  => __( 'Underline', 'portuna-addon' ),
                        'background' => __( 'Background', 'portuna-addon' ),
                    ],
                ]
            );

            $this->add_control(
                'submenu_item_pointer_animation_line',
                [
                    'label'       => __( 'Pointer Animation', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'none',
                    'options'     => [
                        'none'       => __( 'None', 'portuna-addon' ),
                        'fade'       => __( 'Fade', 'portuna-addon' ),
                        'slide'      => __( 'Slide', 'portuna-addon' ),
                        'grow'       => __( 'Grow', 'portuna-addon' ),
                        'drop-in'    => __( 'Drop In', 'portuna-addon' ),
                        'drop-out'    => __( 'Drop Out', 'portuna-addon' ),
                    ],
                    'condition' => [
                        'submenu_item_pointer' => [ 'underline' ],
                    ],
                ]
            );

            $this->add_control(
                'submenu_item_pointer_animation_background',
                [
                    'label'       => __( 'Pointer Animation', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'none',
                    'options'     => [
                        'none'                   => __( 'None', 'portuna-addon' ),
                        'fade'                   => __( 'Fade', 'portuna-addon' ),
                        'grow'                   => __( 'Grow', 'portuna-addon' ),
                        'shrink'                 => __( 'Shrink', 'portuna-addon' ),
                        'sweep-left'             => __( 'Sweep Left', 'portuna-addon' ),
                        'sweep-right'            => __( 'Sweep Right', 'portuna-addon' ),
                        'sweep-up'               => __( 'Seep Up', 'portuna-addon' ),
                        'sweep-down'             => __( 'Seep Down', 'portuna-addon' ),
                        'shutter-in-vertical'    => __( 'Seep Down', 'portuna-addon' ),
                        'shutter-out-vertical'   => __( 'Seep Down', 'portuna-addon' ),
                        'shutter-in-horizontal'  => __( 'Seep Down', 'portuna-addon' ),
                        'shutter-out-horizontal' => __( 'Seep Down', 'portuna-addon' ),
                    ],
                    'condition' => [
                        'submenu_item_pointer' => [ 'background' ],
                    ],
                ]
            );

            $this->add_responsive_control(
                'submenu_padding_items',
                [
                    'label'      => __( 'Padding Items', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_responsive_control(
                'submenu_margin_items',
                [
                    'label'      => __( 'Margin Items', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_control(
                'submenu_border_items',
                [
                    'label'     => __( 'Border Type', 'portuna-addon' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => [
                        'none'   => __( 'None', 'portuna-addon' ),
                        'solid'  => __( 'Solid', 'portuna-addon' ),
                        'double' => __( 'Double', 'portuna-addon' ),
                        'dotted' => __( 'Dotted', 'portuna-addon' ),
                        'dashed' => __( 'Dashed', 'portuna-addon' ),
                        'groove' => __( 'Groove', 'portuna-addon' ),
                    ],
                    'default'   => 'solid',
                    //'selectors' => [
                        //'{{SELECTOR}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-style: {{VALUE}};',
                    //],
                ]
            );

            $this->add_responsive_control(
                'submenu_border_width_items',
                [
                    'label'      => esc_html__( 'Border Width', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'default'    => [
                        'top'    => '1',
                        'right'  => '1',
                        'bottom' => '1',
                        'left'   => '1'
                    ],
                    //'selectors'  => [
                        //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    //],
                    'condition' => [
                        'submenu_border_items!' => 'none',
                    ],
                ]
            );

            $this->start_controls_tabs( 'submenu_effects' );

                $this->start_controls_tab(
                    'submenu_item_normal',
                    [
                        'label' => __( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'submenu_item_color',
                        [
                            'label'     => __( 'Text Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'submenu_item_bgcolor',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'submenu_border_color_items',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'submenu_border_items!' => 'none',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'submenu_item_hover',
                    [
                        'label' => __( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'submenu_item_color_hover',
                        [
                            'label'     => __( 'Text Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link_hover' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'submenu_item_bgcolor_hover',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'submenu_item_pointer!' => 'background'
                            ]
                        ]
                    );

                    $this->add_control(
                        'submenu_item_pointer_color_hover',
                        [
                            'label'     => __( 'Pointer Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link_hover' ] => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'submenu_item_pointer!'  =>  'none'
                            ]
                        ]
                    );

                    $this->add_control(
                        'submenu_border_color_items_hover',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'submenu_border_items!' => 'none',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    // Icon Indicator
    public function style_section4() {
        $this->start_controls_section(
            'section_submenu_icon_item_style',
            [
                'label' => __( 'Icon Indicator', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'icon_indicator_effect' );

                $this->start_controls_tab(
                    'icon_indicator_first_level',
                    [
                        'label' => __( 'First Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_indicator_first_level_heading',
                        [
                            'label'     => __( 'First Level', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before'
                        ]
                    );

                    $this->add_control(
                        'icons_indicator_first_level',
                        [
                            'label'            => __( 'Icon', 'portuna-addon' ),
                            'type'             => Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon_indicator_normal',
                            'default'          => [
                                'value'        => 'fas fa-chevron-down',
                                'library'      => 'fa-solid',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'indicator_size_first_level',
                        [
                            'label' => __( 'Icon Size', 'portuna-addon' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px'  => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                'em'  => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                'rem' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                '%'   => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default'   => [
                                'size'  => 14,
                                'unit'  => 'px'
                            ],
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_submenu_indicator' ] => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'indicator_pos_first_level',
                        [
                            'label'     => __( 'Icon Position', 'portuna-addon' ),
                            'type'      => Controls_Manager::CHOOSE,
                            'toggle'    => false,
                            'options'   => [
                                '1'       => [
                                    'title'  => __( 'Right', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-right',
                                ],
                                '-1'     => [
                                    'title'  => __( 'Left', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-left',
                                ],
                            ],
                            'default'   => '1',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ] => 'order: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'indicator_padding_first_level',
                        [
                            'label'      => __( 'Padding', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'indicator_margin_first_level',
                        [
                            'label'      => __( 'Margin', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'icon_indicator_nested_level',
                    [
                        'label' => __( 'Nested Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icons_indicator_nested_level_heading',
                        [
                            'label'     => __( 'Nested Level', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before'
                        ]
                    );

                    $this->add_control(
                        'icons_indicator_nested_level',
                        [
                            'label'            => __( 'Icon', 'portuna-addon' ),
                            'type'             => Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon_indicator_mega_panel',
                            'default'          => [
                                'value'        => 'fas fa-chevron-right',
                                'library'      => 'fa-solid',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'indicator_size_nested_level',
                        [
                            'label' => __( 'Icon Size', 'portuna-addon' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px'  => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                'em'  => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                'rem' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                '%'   => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default'   => [
                                'size'  => 14,
                                'unit'  => 'px'
                            ],
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_submenu_indicator' ] => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'indicator_pos_nested_level',
                        [
                            'label'     => __( 'Icon Position', 'portuna-addon' ),
                            'type'      => Controls_Manager::CHOOSE,
                            'toggle'    => false,
                            'options'   => [
                                '1'       => [
                                    'title'  => __( 'Right', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-right',
                                ],
                                '-1'     => [
                                    'title'  => __( 'Left', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-left',
                                ],
                            ],
                            'default'   => '1',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ] => 'order: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'indicator_padding_nested_level',
                        [
                            'label'      => __( 'Padding', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'indicator_margin_nested_level',
                        [
                            'label'      => __( 'Margin', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'menu_icon_indicator_heading_first_lvl',
                [
                    'label'     => __( 'Icon Indicator Style (First Level)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->start_controls_tabs( 'icon_indicator_effects_first_lvl' );

                $this->start_controls_tab(
                    'icon_indicator_item_normal_first_lvl',
                    [
                        'label' => __( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_indicator_item_color_first_lvl',
                        [
                            'label'     => __( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_indicator_item_bgcolor_first_lvl',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'icon_indicator_hover_first_lvl',
                    [
                        'label' => __( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_indicator_item_color_hover_first_lvl',
                        [
                            'label'     => __( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_indicator_item_bgcolor_hover_first_lvl',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'menu_icon_indicator_heading_nested_lvl',
                [
                    'label'     => __( 'Icon Indicator Style (Nested Level)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->start_controls_tabs( 'icon_indicator_effects_nested_lvl' );

                $this->start_controls_tab(
                    'icon_indicator_item_normal_nested_lvl',
                    [
                        'label' => __( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_indicator_item_color_nested_lvl',
                        [
                            'label'     => __( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_indicator_item_bgcolor_nested_lvl',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'icon_indicator_hover_nested_lvl',
                    [
                        'label' => __( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_indicator_item_color_hover_nested_lvl',
                        [
                            'label'     => __( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_indicator_item_bgcolor_hover_nested_lvl',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    // Icon
    public function style_section5() {
        $this->start_controls_section(
            'section_icon_item_style',
            [
                'label' => __( 'Icon Item', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'icon_group' );

                $this->start_controls_tab(
                    'icon_first_level',
                    [
                        'label' => __( 'First Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'menu_icon_heading_first_level',
                        [
                            'label'     => __( 'First Level', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_icon_size_first_level',
                        [
                            'label' => __( 'Icon Size', 'portuna-addon' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px'  => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                'em'  => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                'rem' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                '%'   => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default'   => [
                                'size'  => 14,
                                'unit'  => 'px'
                            ],
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_icon' ] => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_icon_pos_first_level',
                        [
                            'label'     => __( 'Icon Position', 'portuna-addon' ),
                            'type'      => Controls_Manager::CHOOSE,
                            'toggle'    => false,
                            'options'   => [
                                '-1'     => [
                                    'title'  => __( 'Left', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-left',
                                ],
                                '1'       => [
                                    'title'  => __( 'Right', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-right',
                                ],
                            ],
                            'default'   => '1',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ] => 'order: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_icon_padding_first_level',
                        [
                            'label'      => __( 'Padding', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_icon_margin_first_level',
                        [
                            'label'      => __( 'Margin', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'icon_nested_level',
                    [
                        'label' => __( 'Nested Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'submenu_icon_heading_nested_level',
                        [
                            'label'     => __( 'Nested Level', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_icon_size_nested_level',
                        [
                            'label' => __( 'Icon Size', 'portuna-addon' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px'  => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                'em'  => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                'rem' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                '%'   => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default'   => [
                                'size'  => 14,
                                'unit'  => 'px'
                            ],
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_submenu_icon' ] => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_icon_pos_nested_level',
                        [
                            'label'     => __( 'Icon Position', 'portuna-addon' ),
                            'type'      => Controls_Manager::CHOOSE,
                            'toggle'    => false,
                            'options'   => [
                                '1'       => [
                                    'title'  => __( 'Right', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-right',
                                ],
                                '-1'     => [
                                    'title'  => __( 'Left', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-left',
                                ],
                            ],
                            'default'   => '1',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ] => 'order: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_icon_padding_nested_level',
                        [
                            'label'      => __( 'Padding', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_icon_margin_nested_level',
                        [
                            'label'      => __( 'Margin', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'icon_heading_first_lvl',
                [
                    'label'     => __( 'Icon Style (First Level)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

                $this->start_controls_tabs( 'icon_effects_first_lvl' );

                $this->start_controls_tab(
                    'icon_normal_first_lvl',
                    [
                        'label' => __( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_color_first_lvl',
                        [
                            'label'     => __( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_bgcolor_first_lvl',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'icon_hover_first_lvl',
                    [
                        'label' => __( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_color_hover_first_lvl',
                        [
                            'label'     => __( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_bgcolor_hover_first_lvl',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'icon_heading_nested_lvl',
                [
                    'label'     => __( 'Icon Style (Nested Level)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->start_controls_tabs( 'icon_effects_nested_lvl' );

                $this->start_controls_tab(
                    'icon_normal_nested_lvl',
                    [
                        'label' => __( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_color_nested_lvl',
                        [
                            'label'     => __( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_bgcolor_nested_lvl',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'icon_hover_nested_lvl',
                    [
                        'label' => __( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_color_hover_nested_lvl',
                        [
                            'label'     => __( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_bgcolor_hover_nested_lvl',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    // Badge
    public function style_section6() {
        $this->start_controls_section(
            'section_badge_item_style',
            [
                'label' => __( 'Badge Item', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'bagde_group' );

                $this->start_controls_tab(
                    'badge_first_level',
                    [
                        'label' => __( 'First Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'label'    => __( 'Badge Background', 'portuna-addon' ),
                            'name'     => 'badge_background_first_lvl',
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ],
                            'exclude'  => [
                                'image'
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'label'    => __( 'Badge Box Shadow', 'portuna-addon' ),
                            'name'     => 'badge_shadow_first_lvl',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ],
                        ]
                    );

                    $this->add_control(
                        'badge_text_color_first_lvl',
                        [
                            'label'     => __( 'Text Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            //'default'   => '#212529',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ] => 'color: {{VALUE}};'
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'badge_padding_first_lvl',
                        [
                            'label'      => __( 'Padding', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'badge_margin_first_lvl',
                        [
                            'label'      => __( 'Margin', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );

                    $this->add_control(
                        'badge_border_type_first_lvl',
                        [
                            'label'     => __( 'Border Type', 'portuna-addon' ),
                            'type'      => Controls_Manager::SELECT,
                            'options'   => [
                                'none'   => __( 'None', 'portuna-addon' ),
                                'solid'  => __( 'Solid', 'portuna-addon' ),
                                'double' => __( 'Double', 'portuna-addon' ),
                                'dotted' => __( 'Dotted', 'portuna-addon' ),
                                'dashed' => __( 'Dashed', 'portuna-addon' ),
                                'groove' => __( 'Groove', 'portuna-addon' ),
                            ],
                            'default'   => 'none',
                            //'selectors' => [
                                //'{{SELECTOR}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-style: {{VALUE}};',
                            //],
                        ]
                    );

                    $this->add_control(
                        'badge_border_color_first_lvl',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'badge_border_type_first_lvl!' => 'none',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'badge_border_width_first_lvl',
                        [
                            'label'      => esc_html__( 'Border Width', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'default'    => [
                                'top'    => '1',
                                'right'  => '1',
                                'bottom' => '1',
                                'left'   => '1'
                            ],
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            //],
                            'condition' => [
                                'badge_border_type_first_lvl!' => 'none',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'badge_border_radius_first_lvl',
                        [
                            'label'      => esc_html__( 'Border Radius', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            //],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'badge_nested_level',
                    [
                        'label' => __( 'Nested Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'label'    => __( 'Badge Background', 'portuna-addon' ),
                            'name'     => 'badge_background_nested_lvl',
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ],
                            'exclude'  => [
                                'image'
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'label'    => __( 'Badge Box Shadow', 'portuna-addon' ),
                            'name'     => 'badge_shadow_nested_lvl',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ],
                        ]
                    );

                    $this->add_control(
                        'badge_text_color_nested_lvl',
                        [
                            'label'     => __( 'Text Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            //'default'   => '#212529',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ] => 'color: {{VALUE}};'
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'badge_padding_nested_lvl',
                        [
                            'label'      => __( 'Padding', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'badge_margin_nested_lvl',
                        [
                            'label'      => __( 'Margin', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );

                    $this->add_control(
                        'badge_border_type_nested_lvl',
                        [
                            'label'     => __( 'Border Type', 'portuna-addon' ),
                            'type'      => Controls_Manager::SELECT,
                            'options'   => [
                                'none'   => __( 'None', 'portuna-addon' ),
                                'solid'  => __( 'Solid', 'portuna-addon' ),
                                'double' => __( 'Double', 'portuna-addon' ),
                                'dotted' => __( 'Dotted', 'portuna-addon' ),
                                'dashed' => __( 'Dashed', 'portuna-addon' ),
                                'groove' => __( 'Groove', 'portuna-addon' ),
                            ],
                            'default'   => 'none',
                            //'selectors' => [
                                //'{{SELECTOR}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-style: {{VALUE}};',
                            //],
                        ]
                    );

                    $this->add_control(
                        'badge_border_color_nested_lvl',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'badge_border_type_nested_lvl!' => 'none',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'badge_border_width_nested_lvl',
                        [
                            'label'      => esc_html__( 'Border Width', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'default'    => [
                                'top'    => '1',
                                'right'  => '1',
                                'bottom' => '1',
                                'left'   => '1'
                            ],
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            //],
                            'condition' => [
                                'badge_border_type_nested_lvl!' => 'none',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'badge_border_radius_nested_lvl',
                        [
                            'label'      => esc_html__( 'Border Radius', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            //],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'badge_heading_general',
                [
                    'label'     => __( 'Badge General', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label'           => __( 'Badge Typography', 'portuna-addon' ),
                    'name'            => 'badge_typo',
                    //'selector'        => '{{WRAPPER}}' . self::$css_map[ '' ],
                    'fields_options'  => [
                        'font_weight' => [
                            'default' => '400'
                        ]
                    ]
                ]
            );

        $this->end_controls_section();
    }

    // Hamburger
    public function style_section7() {
        $this->start_controls_section(
            'section_hamburger_style',
            [
                'label' => __( 'Hamburger Options', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'device_general',
                [
                    'label'     => __( 'General Options', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'device_breakpoint_hamburger',
                [
                    'label'        => __( 'Enable Breakpoint?', 'portuna-addon' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => 'yes',
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'device_breakpoint_responsive',
                [
                    'label'       => __( 'Breakpoint', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'mobile',
                    'options'     => [
                        'tablet'    => __( 'Tablet (< 1025px)', 'portuna-addon' ),
                        'mobile'    => __( 'Mobile (< 768px)', 'portuna-addon' ),
                        'custom'    => __( 'Custom (<)', 'portuna-addon' ),
                    ],
                    'condition'   => [
                        'device_breakpoint_hamburger' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'device_breakpoint_customize_responsive',
                [
                    'label'              => __( 'Custom Number (< px)', 'portuna-addon' ),
                    'type'               => Controls_Manager::NUMBER,
                    'default'            => 1000,
                    'min'                => 1,
                    'required'           => true,
                    'description'        => __( 'Set your responsive number (px) on which the hamburger menu should appear.', 'portuna-addon' ),
                    'frontend_available' => true,
                    'condition'          => [
                        'device_breakpoint_hamburger'  => 'yes',
                        'device_breakpoint_responsive' => 'custom'
                    ],
                ]
            );

            $this->add_control(
                'device_full_width',
                [
                    'label'              => __( 'Full Width', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'description'        => __( 'Stretch the dropdown of the menu to full width.', 'portuna-addon' ),
                    'prefix_class'       => 'elementor-nav-menu--',
                    'return_value'       => 'stretch',
                    'frontend_available' => true,
                    'condition'          => [
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'hamburger_toggle_align',
                [
                    'label'   => __( 'Toggle Align', 'portuna-addon' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'default' => 'center',
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'portuna-addon' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'portuna-addon' ),
                            'icon'  => 'eicon-h-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'portuna-addon' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                    ],
                    'selectors_dictionary' => [
                        'left'   => 'margin-right: auto',
                        'center' => 'margin: 0 auto',
                        'right'  => 'margin-left: auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .' => '{{VALUE}}',
                    ],
                    'condition' => [
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'hamburger_padding',
                [
                    'label'      => __( 'Padding', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                    'separator' => 'before',
                    'condition' => [
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'hamburger_margin',
                [
                    'label'      => __( 'Margin', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                    'condition' => [
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'hamburger_border_type',
                [
                    'label'     => __( 'Border Type', 'portuna-addon' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => [
                        'none'   => __( 'None', 'portuna-addon' ),
                        'solid'  => __( 'Solid', 'portuna-addon' ),
                        'double' => __( 'Double', 'portuna-addon' ),
                        'dotted' => __( 'Dotted', 'portuna-addon' ),
                        'dashed' => __( 'Dashed', 'portuna-addon' ),
                        'groove' => __( 'Groove', 'portuna-addon' ),
                    ],
                    'default'   => 'none',
                    //'selectors' => [
                        //'{{SELECTOR}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-style: {{VALUE}};',
                    //],
                    'condition' => [
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'hamburger_border_width',
                [
                    'label'      => esc_html__( 'Border Width', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'default'    => [
                        'top'    => '1',
                        'right'  => '1',
                        'bottom' => '1',
                        'left'   => '1'
                    ],
                    //'selectors'  => [
                        //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    //],
                    'condition' => [
                        'hamburger_border_type!'       => 'none',
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'hamburger_border_radius',
                [
                    'label'      => esc_html__( 'Border Radius', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    //'selectors'  => [
                        //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    //],
                    'condition' => [
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'hamburger_toggle_heading',
                [
                    'label'     => __( 'Hamburger (Toggle)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'hamburger_open_icons',
                [
                    'label'            => __( 'Open Icon', 'portuna-addon' ),
                    'type'             => Controls_Manager::ICONS,
                    'fa4compatibility' => 'hamburger_open_icon',
                    'default'          => [
                        'value'        => 'fas fa-bars',
                        'library'      => 'fa-solid',
                    ],
                    'condition' => [
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'hamburger_close_heading',
                [
                    'label'     => __( 'Hamburger (Close)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'hamburger_close_icons',
                [
                    'label'            => __( 'Close Icon', 'portuna-addon' ),
                    'type'             => Controls_Manager::ICONS,
                    'fa4compatibility' => 'hamburger_close_icon',
                    'default'          => [
                        'value'        => 'fas fa-times',
                        'library'      => 'fa-solid',
                    ],
                    'condition' => [
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->start_controls_tabs( 'hamburger_group' );

                $this->start_controls_tab(
                    'hamburger_normal',
                    [
                        'label'     => __( 'Normal', 'portuna-addon' ),
                        'condition' => [
                            'device_breakpoint_hamburger'  => 'yes',
                        ],
                    ]
                );

                    $this->add_control(
                        'hamburger_text_color',
                        [
                            'label'      => esc_html__( 'Icon Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'device_breakpoint_hamburger'  => 'yes',
                            ],
                        ]
                    );

                    $this->add_control(
                        'hamburger_bg_color',
                        [
                            'label'      => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'device_breakpoint_hamburger'  => 'yes',
                            ],
                        ]
                    );

                    $this->add_control(
                        'hamburger_border_color',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'hamburger_border_type!'       => 'none',
                                'device_breakpoint_hamburger'  => 'yes',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'hamburger_hover',
                    [
                        'label'     => __( 'Hover', 'portuna-addon' ),
                        'condition' => [
                            'device_breakpoint_hamburger'  => 'yes',
                        ],
                    ]
                );

                    $this->add_control(
                        'hamburger_text_color_hover',
                        [
                            'label'      => esc_html__( 'Icon Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'device_breakpoint_hamburger'  => 'yes',
                            ],
                        ]
                    );

                    $this->add_control(
                        'hamburger_bg_color_hover',
                        [
                            'label'      => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'device_breakpoint_hamburger'  => 'yes',
                            ],
                        ]
                    );

                    $this->add_control(
                        'hamburger_border_color_hover',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'hamburger_border_type!'       => 'none',
                                'device_breakpoint_hamburger'  => 'yes',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'hamburger_active',
                    [
                        'label'     => __( 'Active', 'portuna-addon' ),
                        'condition' => [
                            'device_breakpoint_hamburger'  => 'yes',
                        ],
                    ]
                );

                    $this->add_control(
                        'hamburger_text_color_active',
                        [
                            'label'      => esc_html__( 'Icon Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'device_breakpoint_hamburger'  => 'yes',
                            ],
                        ]
                    );

                    $this->add_control(
                        'hamburger_bg_color_active',
                        [
                            'label'      => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'condition' => [
                                'device_breakpoint_hamburger'  => 'yes',
                            ],
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                        ]
                    );

                    $this->add_control(
                        'hamburger_border_color_active',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            'condition'  => [
                                'hamburger_border_type!'       => 'none',
                                'device_breakpoint_hamburger'  => 'yes',
                            ],
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Get available menus list
     *
     * @return array
     */
    public function get_available_menus() {
        $raw_menus = wp_get_nav_menus();
        $menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );
        $parent    = isset( $_GET[ 'parent_menu' ] ) ? absint( $_GET[ 'parent_menu' ] ) : 0;

        if ( 0 < $parent && isset( $menus[ $parent ] ) ) {
            unset( $menus[ $parent ] );
        }

        return $menus;
    }

    /**
     * Render Frontend Output – The render() method, which is where you actually
     * render the code and generate the final HTML on the frontend using PHP.
     */
    protected function render() {
        $this->server_side_render();
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new MegaMenu() );