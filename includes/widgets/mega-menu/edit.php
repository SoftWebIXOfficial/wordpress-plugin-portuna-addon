<?php

namespace PortunaAddon\Widgets\Edit;

defined( 'ABSPATH' ) || exit;

use \Elementor\Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Repeater;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Box_Shadow;


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
//         wp_register_script(
//             'mega-menu-script-layout1',
//             plugin_dir_url( dirname( __FILE__ ) ) . 'mega-menu/assets/js/layout1.min.js',
//             [],
//             null
//         );
    }

    public function get_style_depends() {
        return [ 'mega-menu-style-layout1' ];
    }

//     public function get_script_depends() {
//     	//return [ 'jquery', 'mega-menu-script-layout1' ];
//     }

    /**
     * Map the widget controls.
     * Used to allow the user to customize items.
     */
    private static $css_map = [
        'wrap_menu'                                 => ' .portuna-addon-menu-items',
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
        return 'sm sm-accordion';
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
                    'label'       => __( 'Menu Type Orientation', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'horizontal',
                    'options'     => [
                        'horizontal'  => __( 'Horizontal', 'portuna-addon' ),
                        'vertical'    => __( 'Vertical', 'portuna-addon' ),
                        'dropdown'    => __( 'Dropdown', 'portuna-addon' ),
                    ],
                ]
            );

        $this->end_controls_section();
    }

    public function style_section1() {
        $this->start_controls_section(
            'section_general_style',
            [
                'label' => __( 'General Options', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'general_menu_width',
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
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ] => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'general_menu_alignment',
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
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ] => 'justify-content: {{VALUE}}',
                    ],
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
                    'default'    => [
                        'top'    => '7',
                        'right'  => '32',
                        'bottom' => '7',
                        'left'   => '32',
                        'unit'   => 'px'
                    ],
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
                        'menu_item_pointer_color_hover',
                        [
                            'label'     => __( 'Pointer Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link_hover' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

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
                        'submenu_normal_panel_width',
                        [
                            'label'    => __( 'Width', 'portuna-addon' ),
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
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_submenu' ] => 'max-width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'submenu_mega_panel',
                    [
                        'label' => __( 'Mega Panel', 'portuna-addon' ),
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
                    'separator' => 'before'
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
                        'submenu_item_pointer_color_hover',
                        [
                            'label'     => __( 'Pointer Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link_hover' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

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

        $this->end_controls_section();
    }

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
                        'menu_icon_heading',
                        [
                            'label'     => __( 'First Level', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_icon_size',
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

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'icon_nested_level',
                    [
                        'label' => __( 'Nested Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'submenu_icon_heading',
                        [
                            'label'     => __( 'Nested Level', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_icon_size',
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

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

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
                        Group_Control_Typography::get_type(),
                        [
                            'label'           => __( 'Badge Typography', 'portuna-addon' ),
                            'name'            => 'badge_first_typo',
                            //'selector'        => '{{WRAPPER}}' . self::$css_map[ '' ],
                            'fields_options'  => [
                                'font_weight' => [
                                    'default' => '400'
                                ]
                            ]
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
                        Group_Control_Typography::get_type(),
                        [
                            'label'           => __( 'Badge Typography', 'portuna-addon' ),
                            'name'            => 'badge_nested_typo',
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

        $this->end_controls_section();
    }

    public function style_section7() {
        $this->start_controls_section(
            'section_humburger_style',
            [
                'label' => __( 'Humburger Options', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

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