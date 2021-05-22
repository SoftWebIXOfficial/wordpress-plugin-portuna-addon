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

        // Styles.
        wp_register_style(
            'mega-menu-style-layout1',
            plugin_dir_url( dirname( __FILE__ ) ) . 'mega-menu/assets/css/layout1.min.css',
            [],
            null
        );

        // Scripts.
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
        'wrap_menu_horizontal'                      => ' .portuna-addon-menu-items.portuna-addon-top-menu',
        'wrap_menu_vertical'                        => ' .portuna-addon-menu-vertical .portuna-addon-menu-items:not(.portuna-addon-sub-menu)',
        'wrap_topmenu_container'                    => ' .portuna-addon-top-menu > .portuna-addon-menu-item',
        'wrap_submenu_container'                    => ' .portuna-addon-sub-menu',
        'wrap_submenu_content'                      => ' div.portuna-addon-sub-menu-content',
        'wrap_submegamenu_container'                => ' ul.portuna-addon-sub-mega-menu',
        'wrap_menu_container'                         => ' .portuna-addon--mega-menu--content > .portuna-addon-menu-items',
        'wrap_menu_content'                         => ' .portuna-addon-menu__wrapper-content',
        'wrap_menu_link'                            => ' .portuna-addon-menu__wrapper-content-link > a',
        'wrap_menu_item_link'                       => ' .portuna-addon-top-menu .portuna-addon-menu-item .first-level-link, .portuna-addon-sub-menu .portuna-addon-menu-item .nested-level-link',
        'wrap_menu_item_link_hover'                 => ' .portuna-addon-top-menu .portuna-addon-menu-item:hover .first-level-link, .portuna-addon-sub-menu .portuna-addon-menu-item:hover .nested-level-link',
        'wrap_menu_top_icon_dropdown_arrow'         => ' .portuna-addon-top-menu .portuna-addon-menu-item > .portuna-addon-menu__wrapper-content .portuna-addon-menu-dropdown',
        'wrap_menu_top_icon_dropdown_arrow_hover'   => ' .portuna-addon-top-menu > .portuna-addon-menu-item:hover > .portuna-addon-menu__wrapper-content .portuna-addon-menu-dropdown',
        'wrap_menu_nested_icon_dropdown_arrow'      => ' .portuna-addon-sub-menu .portuna-addon-menu-item > .portuna-addon-menu__wrapper-content .portuna-addon-menu-dropdown',
        'wrap_menu_nested_icon_dropdown_arrow_hover'=> ' .portuna-addon-sub-menu .portuna-addon-menu-item:hover > .portuna-addon-menu__wrapper-content .portuna-addon-menu-dropdown',
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
        return esc_html__( 'Mega Menu', 'portuna-addon' );
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

        // Initialize contents sections.
        $this->content_MenuSettings();

        // Initialize styles sections.
        $this->style_GeneralOptions();
        $this->style_MenuFirstLevel();
        $this->style_SubMenuNestedLevel();
        $this->style_IconDropdown();
        $this->style_IconItem();
        $this->style_BadgeItem();
        $this->style_HamburgerOptions();
    }

    public function content_MenuSettings() {

        $this->start_controls_section(
            'section_content_menu',
            [
                'label' => esc_html__( 'Menu Settings', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'menu_list',
                [
                    'label'       => esc_html__( 'Choose Menus', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '',
                    'options'     => $this->get_available_menus(),
                    'description' => sprintf(
                        '<a href="%s" target="_blank">%s</a>',
                        admin_url( 'nav-menus.php' ),
                        esc_html__( 'Manage Menus', 'portuna-addon' )
                    ),
                ]
            );

            $this->add_control(
                'menu_type_orientation',
                [
                    'label'              => esc_html__( 'Menu Type Orientation', 'portuna-addon' ),
                    'type'               => Controls_Manager::SELECT,
                    'default'            => 'horizontal',
                    'options'            => [
                        'horizontal'     => esc_html__( 'Horizontal', 'portuna-addon' ),
                        'vertical'       => esc_html__( 'Vertical', 'portuna-addon' ),
                        'dropdown'       => esc_html__( 'Dropdown', 'portuna-addon' ),
                    ],
                    'prefix_class'       => 'portuna-addon-menu-',
                    'style_transfer'     => true,
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'menu_type_animation_hr',
                [
                    'label'       => esc_html__( 'Menu Animation', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'fade',
                    'options'     => [
                        'fade'        => esc_html__( 'Fade', 'portuna-addon' ),
                        'fade-up'     => esc_html__( 'Fade Up', 'portuna-addon' ),
                        'fade-down'   => esc_html__( 'Fade Down', 'portuna-addon' ),
                    ],
                    'condition'   => [
                        'menu_type_orientation' => 'horizontal'
                    ],
                ]
            );

            $this->add_control(
                'menu_type_side_position',
                [
                    'label'       => esc_html__( 'Sub Menu Side Position', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'right-side',
                    'options'     => [
                        'left-side'   => esc_html__( 'Left Side', 'portuna-addon' ),
                        'right-side'  => esc_html__( 'Right Side', 'portuna-addon' ),
                    ],
                    'condition'   => [
                        'menu_type_orientation' => 'vertical'
                    ],
                ]
            );

        $this->end_controls_section();
    }

    // General Option
    public function style_GeneralOptions() {

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => esc_html__( 'General Options', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'general_menu_width_hr',
                [
                    'label'    => esc_html__( 'Menu Width', 'portuna-addon' ),
                    'type'     => Controls_Manager::SLIDER,
                    'range'    => [
                        'px' => [
                            'min' => 0,
                            'max' => 1960,
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
                    'label'    => esc_html__( 'Menu Width', 'portuna-addon' ),
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
                    'label'     => esc_html__( 'Menu Alignment', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'toggle'    => false,
                    'options'   => [
                        'flex-start'     => [
                            'title'  => esc_html__( 'Left', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-left',
                        ],
                        'center'         => [
                            'title'  => esc_html__( 'Center', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-center',
                        ],
                        'flex-end'       => [
                            'title'  => esc_html__( 'Right', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-right',
                        ],
                        'space-between'  => [
                            'title'  => esc_html__( 'Right', 'portuna-addon' ),
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
                    'label'     => esc_html__( 'Menu Alignment', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'toggle'    => false,
                    'options'   => [
                        'left'     => [
                            'title'  => esc_html__( 'Left', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-left',
                        ],
                        'center'         => [
                            'title'  => esc_html__( 'Center', 'portuna-addon' ),
                            'icon'   => 'eicon-h-align-center',
                        ],
                        'right'       => [
                            'title'  => esc_html__( 'Right', 'portuna-addon' ),
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

            $this->add_control(
                'general_menu_background',
                [
                    'label'     => esc_html__( 'Menu Background Color', 'portuna-addon' ),
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
                    'label'    => esc_html__( 'Menu Box Shadow', 'portuna-addon' ),
                    'name'     => 'general_menu_box_shadow',
                    'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label'           => esc_html__( 'Menu Item Typography', 'portuna-addon' ),
                    'name'            => 'menu_typography',
                    'selector'        => '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ],
                    'fields_options'  => [
                        'font_weight' => [
                            'default' => '400'
                        ]
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'general_menu_padding',
                [
                    'label'      => esc_html__( 'Menu Padding', 'portuna-addon' ),
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
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_container' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                    'separator' => 'before'
                ]
            );
            
            $this->add_responsive_control(
                'general_menu_radius',
                [
                    'label'      => esc_html__( 'Menu Radius', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default'    => [
                        'top'    => '0',
                        'right'  => '0',
                        'bottom' => '0',
                        'left'   => '0',
                        'unit'   => 'px'
                    ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_container' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

        $this->end_controls_section();
    }

    // Menu (First Level)
    public function style_MenuFirstLevel() {

        $this->start_controls_section(
            'section_menu_item_style',
            [
                'label' => esc_html__( 'Menu (First Level)', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'menu_item_pointer',
                [
                    'label'       => esc_html__( 'Pointer (Hover)', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'none',
                    'options'     => [
                        'none'       => esc_html__( 'None', 'portuna-addon' ),
                        'underline'  => esc_html__( 'Underline', 'portuna-addon' ),
                        'background' => esc_html__( 'Background', 'portuna-addon' ),
                    ],
                ]
            );

            $this->add_control(
                'menu_item_pointer_animation_line',
                [
                    'label'       => esc_html__( 'Pointer Animation', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'none',
                    'options'     => [
                        'none'       => esc_html__( 'None', 'portuna-addon' ),
                        'fade'       => esc_html__( 'Fade', 'portuna-addon' ),
                        'slide'      => esc_html__( 'Slide', 'portuna-addon' ),
                        'grow'       => esc_html__( 'Grow', 'portuna-addon' ),
                        'drop-in'    => esc_html__( 'Drop In', 'portuna-addon' ),
                        'drop-out'   => esc_html__( 'Drop Out', 'portuna-addon' ),
                    ],
                    'condition' => [
                        'menu_item_pointer' => [ 'underline' ],
                    ],
                ]
            );

            $this->add_control(
                'menu_item_pointer_animation_background',
                [
                    'label'       => esc_html__( 'Pointer Animation', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'none',
                    'options'     => [
                        'none'                   => esc_html__( 'None', 'portuna-addon' ),
                        'fade'                   => esc_html__( 'Fade', 'portuna-addon' ),
                        'grow'                   => esc_html__( 'Grow', 'portuna-addon' ),
                        'shrink'                 => esc_html__( 'Shrink', 'portuna-addon' ),
                        'sweep-left'             => esc_html__( 'Sweep Left', 'portuna-addon' ),
                        'sweep-right'            => esc_html__( 'Sweep Right', 'portuna-addon' ),
                        'sweep-up'               => esc_html__( 'Seep Up', 'portuna-addon' ),
                        'sweep-down'             => esc_html__( 'Seep Down', 'portuna-addon' ),
                        'shutter-in-vertical'    => esc_html__( 'Seep Down', 'portuna-addon' ),
                        'shutter-out-vertical'   => esc_html__( 'Seep Down', 'portuna-addon' ),
                        'shutter-in-horizontal'  => esc_html__( 'Seep Down', 'portuna-addon' ),
                        'shutter-out-horizontal' => esc_html__( 'Seep Down', 'portuna-addon' ),
                    ],
                    'condition' => [
                        'menu_item_pointer' => [ 'background' ],
                    ],
                ]
            );

            $this->add_responsive_control(
                'menu_item_padding',
                [
                    'label'      => esc_html__( 'Padding', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default'    => [
                        'top'    => '0',
                        'right'  => '20',
                        'bottom' => '10',
                        'left'   => '0',
                        'unit'   => 'px'
                    ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_topmenu_container' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_responsive_control(
                'menu_item_margin',
                [
                    'label'      => esc_html__( 'Margin', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_topmenu_container' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_control(
                'menu_heading_items',
                [
                    'label'     => esc_html__( 'Menu Items', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->start_controls_tabs( 'menu_effects' );

                $this->start_controls_tab(
                    'menu_item_normal',
                    [
                        'label' => esc_html__( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'menu_item_color',
                        [
                            'label'     => esc_html__( 'Text Color', 'portuna-addon' ),
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
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
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
                        'label' => esc_html__( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'menu_item_color_hover',
                        [
                            'label'     => esc_html__( 'Text Color', 'portuna-addon' ),
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
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
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
                            'label'     => esc_html__( 'Pointer Color', 'portuna-addon' ),
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

    // Sub Menu (Nested Level)
    public function style_SubMenuNestedLevel() {

        $this->start_controls_section(
            'section_submenu_style',
            [
                'label' => esc_html__( 'Sub Menu (Nested Level)', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'submenu_group' );

                $this->start_controls_tab(
                    'submenu_simple_panel',
                    [
                        'label' => esc_html__( 'Simple Panel', 'portuna-addon' ),
                    ]
                );

                    $this->add_responsive_control(
                        'submenu_simple_panel_width_hr',
                        [
                            'label'    => esc_html__( 'Width', 'portuna-addon' ),
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
                        'submenu_simple_panel_width_vr',
                        [
                            'label'    => esc_html__( 'Width', 'portuna-addon' ),
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
                            'label'    => esc_html__( 'Background', 'portuna-addon' ),
                            'name'     => 'submenu_simple_panel_background',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_submenu_container' ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'label'          => esc_html__( 'Box Shadow', 'portuna-addon' ),
                            'name'           => 'submenu_simple_panel_shadow',
                            'selector'       => '{{WRAPPER}}' . self::$css_map[ 'wrap_submenu_container' ],
                            'fields_options' => [
                                'box_shadow_type' => [
                                    'default'     => 'yes',
                                ],
                                'box_shadow'  => [
                                    'default' => [
                                        'horizontal' => 0,
                                        'vertical'   => 0,
                                        'blur'       => 7,
                                        'spread'     => 0,
                                        'color'      => 'rgba(0,0,0,0.4)',
                                    ],
                                ],
                            ],
                        ]
                    );

                    $this->add_control(
                        'submenu_simple_panel_heading_container',
                        [
                            'label'     => esc_html__( 'Sub Menu (Container)', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_simple_panel_padding',
                        [
                            'label'      => esc_html__( 'Padding Container', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_simple_panel_margin',
                        [
                            'label'      => esc_html__( 'Margin Container', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );

                    $this->add_control(
                        'submenu_simple_panel_border',
                        [
                            'label'      => esc_html__( 'Border Type', 'portuna-addon' ),
                            'type'       => Controls_Manager::SELECT,
                            'options'    => [
                                'none'   => esc_html__( 'None', 'portuna-addon' ),
                                'solid'  => esc_html__( 'Solid', 'portuna-addon' ),
                                'double' => esc_html__( 'Double', 'portuna-addon' ),
                                'dotted' => esc_html__( 'Dotted', 'portuna-addon' ),
                                'dashed' => esc_html__( 'Dashed', 'portuna-addon' ),
                                'groove' => esc_html__( 'Groove', 'portuna-addon' ),
                            ],
                            'default'    => 'none',
                            //'selectors' => [
                                //'{{SELECTOR}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-style: {{VALUE}};',
                            //],
                        ]
                    );

                    $this->add_control(
                        'submenu_simple_panel_border_color',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'submenu_simple_panel_border!' => 'none',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_simple_panel_border_width',
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
                                'submenu_simple_panel_border!' => 'none',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_simple_panel_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            //],
                        ]
                    );
                    
                    $this->add_control(
                        'submenu_simple_panel_heading_items',
                        [
                            'label'     => esc_html__( 'Sub Menu (Items)', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                    );
        
                    $this->add_control(
                        'submenu_simple_panel_item_pointer',
                        [
                            'label'       => esc_html__( 'Pointer (Hover)', 'portuna-addon' ),
                            'type'        => Controls_Manager::SELECT,
                            'default'     => 'none',
                            'options'     => [
                                'none'       => esc_html__( 'None', 'portuna-addon' ),
                                'underline'  => esc_html__( 'Underline', 'portuna-addon' ),
                                'background' => esc_html__( 'Background', 'portuna-addon' ),
                            ],
                        ]
                    );
        
                    $this->add_control(
                        'submenu_simple_panel_item_animation',
                        [
                            'label'       => esc_html__( 'Pointer Animation', 'portuna-addon' ),
                            'type'        => Controls_Manager::SELECT,
                            'default'     => 'none',
                            'options'     => [
                                'none'       => esc_html__( 'None', 'portuna-addon' ),
                                'fade'       => esc_html__( 'Fade', 'portuna-addon' ),
                                'slide'      => esc_html__( 'Slide', 'portuna-addon' ),
                                'grow'       => esc_html__( 'Grow', 'portuna-addon' ),
                                'drop-in'    => esc_html__( 'Drop In', 'portuna-addon' ),
                                'drop-out'   => esc_html__( 'Drop Out', 'portuna-addon' ),
                            ],
                            'condition' => [
                                'submenu_simple_panel_item_pointer' => [ 'underline' ],
                            ],
                        ]
                    );
        
                    $this->add_control(
                        'submenu_simple_panel_item_animation_background',
                        [
                            'label'       => esc_html__( 'Pointer Animation', 'portuna-addon' ),
                            'type'        => Controls_Manager::SELECT,
                            'default'     => 'none',
                            'options'     => [
                                'none'                   => esc_html__( 'None', 'portuna-addon' ),
                                'fade'                   => esc_html__( 'Fade', 'portuna-addon' ),
                                'grow'                   => esc_html__( 'Grow', 'portuna-addon' ),
                                'shrink'                 => esc_html__( 'Shrink', 'portuna-addon' ),
                                'sweep-left'             => esc_html__( 'Sweep Left', 'portuna-addon' ),
                                'sweep-right'            => esc_html__( 'Sweep Right', 'portuna-addon' ),
                                'sweep-up'               => esc_html__( 'Seep Up', 'portuna-addon' ),
                                'sweep-down'             => esc_html__( 'Seep Down', 'portuna-addon' ),
                                'shutter-in-vertical'    => esc_html__( 'Seep Down', 'portuna-addon' ),
                                'shutter-out-vertical'   => esc_html__( 'Seep Down', 'portuna-addon' ),
                                'shutter-in-horizontal'  => esc_html__( 'Seep Down', 'portuna-addon' ),
                                'shutter-out-horizontal' => esc_html__( 'Seep Down', 'portuna-addon' ),
                            ],
                            'condition' => [
                                'submenu_simple_panel_item_pointer' => [ 'background' ],
                            ],
                        ]
                    );
        
                    $this->add_responsive_control(
                        'submenu_simple_panel_padding_items',
                        [
                            'label'      => esc_html__( 'Padding Items', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );
        
                    $this->add_responsive_control(
                        'submenu_simple_panel_margin_items',
                        [
                            'label'      => esc_html__( 'Margin Items', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_link' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );
        
                    $this->add_control(
                        'submenu_simple_panel_border_items',
                        [
                            'label'      => esc_html__( 'Border Type', 'portuna-addon' ),
                            'type'       => Controls_Manager::SELECT,
                            'options'    => [
                                'none'   => esc_html__( 'None', 'portuna-addon' ),
                                'solid'  => esc_html__( 'Solid', 'portuna-addon' ),
                                'double' => esc_html__( 'Double', 'portuna-addon' ),
                                'dotted' => esc_html__( 'Dotted', 'portuna-addon' ),
                                'dashed' => esc_html__( 'Dashed', 'portuna-addon' ),
                                'groove' => esc_html__( 'Groove', 'portuna-addon' ),
                            ],
                            'default'    => 'solid',
                            //'selectors' => [
                                //'{{SELECTOR}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-style: {{VALUE}};',
                            //],
                        ]
                    );
        
                    $this->add_responsive_control(
                        'submenu_simple_panel_border_width_items',
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
                                'submenu_simple_panel_border_items!' => 'none',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'submenu_mega_panel',
                    [
                        'label' => esc_html__( 'Mega Panel', 'portuna-addon' ),
                    ]
                );

                    $this->add_responsive_control(
                        'submenu_mega_panel_width_hr',
                        [
                            'label'    => esc_html__( 'Width', 'portuna-addon' ),
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
                        'submenu_mega_panel_width_vr',
                        [
                            'label'    => esc_html__( 'Width', 'portuna-addon' ),
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

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'label'    => esc_html__( 'Background', 'portuna-addon' ),
                            'name'     => 'submenu_mega_panel_background',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_submegamenu_container' ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'label'          => esc_html__( 'Box Shadow', 'portuna-addon' ),
                            'name'           => 'submenu_mega_panel_shadow',
                            'selector'       => '{{WRAPPER}}' . self::$css_map[ 'wrap_submegamenu_container' ],
                            'fields_options' => [
                                'box_shadow_type' => [
                                    'default' => 'yes',
                                ],
                                'box_shadow'  => [
                                    'default' => [
                                        'horizontal' => 0,
                                        'vertical'   => 0,
                                        'blur'       => 7,
                                        'spread'     => 0,
                                        'color'      => 'rgba(0,0,0,0.4)',
                                    ],
                                ],
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'submenu_simple_panel_heading_states',
                [
                    'label'     => esc_html__( 'Sub Menu (States)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->start_controls_tabs( 'submenu_simple_panel_states' );

                $this->start_controls_tab(
                    'submenu_simple_panel_item_normal',
                    [
                        'label' => esc_html__( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'submenu_simple_panel_item_color',
                        [
                            'label'     => esc_html__( 'Text Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'submenu_simple_panel_item_bgcolor',
                        [
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'submenu_simple_panel_items_border_color',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'submenu_simple_panel_border_items!' => 'none',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'submenu_simple_panel_item_hover',
                    [
                        'label' => esc_html__( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'submenu_simple_panel_item_color_hover',
                        [
                            'label'     => esc_html__( 'Text Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link_hover' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'submenu_simple_panel_item_bgcolor_hover',
                        [
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link' ] => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'submenu_simple_panel_item_pointer!' => 'background'
                            ]
                        ]
                    );

                    $this->add_control(
                        'submenu_simple_panel_item_pointer_color_hover',
                        [
                            'label'     => esc_html__( 'Pointer Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_item_link_hover' ] => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'submenu_simple_panel_item_pointer!'  =>  'none'
                            ]
                        ]
                    );

                    $this->add_control(
                        'submenu_simple_panel_item_border_color_hover',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            //'selectors'  => [
                                //'{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            //],
                            'condition' => [
                                'submenu_simple_panel_border_items!' => 'none',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    // Icon (Dropdown)
    public function style_IconDropdown() {

        $this->start_controls_section(
            'section_icon_dropdown_style',
            [
                'label' => esc_html__( 'Icon (Dropdown)', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'icon_dropdown_tabs' );

                $this->start_controls_tab(
                    'icon_dropdown_first_level',
                    [
                        'label' => esc_html__( 'First Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_dropdown_first_level_heading',
                        [
                            'label'     => esc_html__( 'First Level', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before'
                        ]
                    );

                    $this->add_control(
                        'icons_dropdown_first_level',
                        [
                            'label'            => esc_html__( 'Icon', 'portuna-addon' ),
                            'type'             => Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon_dropdown_first_level',
                            'default'          => [
                                'value'        => 'fas fa-chevron-down',
                                'library'      => 'fa-solid',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'icon_dropdown_size_first_level',
                        [
                            'label' => esc_html__( 'Icon Size', 'portuna-addon' ),
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
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_top_icon_dropdown_arrow' ]          => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_top_icon_dropdown_arrow' ] . ' svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'icon_dropdown_order_first_level',
                        [
                            'label'     => esc_html__( 'Icon Position', 'portuna-addon' ),
                            'type'      => Controls_Manager::CHOOSE,
                            'toggle'    => false,
                            'options'   => [
                                '-1'     => [
                                    'title'  => esc_html__( 'Left', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-left',
                                ],
                                '1'       => [
                                    'title'  => esc_html__( 'Right', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-right',
                                ],
                            ],
                            'default'   => '1',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_top_icon_dropdown_arrow' ] => 'order: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'icon_dropdown_padding_first_level',
                        [
                            'label'      => esc_html__( 'Padding', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_top_icon_dropdown_arrow' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'icon_dropdown_margin_first_level',
                        [
                            'label'      => esc_html__( 'Margin', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default'    => [
                                'top'    => '0',
                                'right'  => '5',
                                'bottom' => '0',
                                'left'   => '5',
                                'unit'   => 'px'
                            ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_top_icon_dropdown_arrow' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'icon_dropdown_nested_level',
                    [
                        'label' => esc_html__( 'Nested Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_dropdown_nested_level_heading',
                        [
                            'label'     => esc_html__( 'Nested Level', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before'
                        ]
                    );

                    $this->add_control(
                        'icons_dropdown_nested_level',
                        [
                            'label'            => esc_html__( 'Icon', 'portuna-addon' ),
                            'type'             => Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon_dropdown_nested_level',
                            'default'          => [
                                'value'        => 'fas fa-chevron-right',
                                'library'      => 'fa-solid',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'icon_dropdown_size_nested_level',
                        [
                            'label' => esc_html__( 'Icon Size', 'portuna-addon' ),
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
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_nested_icon_dropdown_arrow' ]          => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_nested_icon_dropdown_arrow' ] . ' svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'icon_dropdown_order_nested_level',
                        [
                            'label'     => esc_html__( 'Icon Position', 'portuna-addon' ),
                            'type'      => Controls_Manager::CHOOSE,
                            'toggle'    => false,
                            'options'   => [
                                '1'       => [
                                    'title'  => esc_html__( 'Right', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-right',
                                ],
                                '-1'     => [
                                    'title'  => esc_html__( 'Left', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-left',
                                ],
                            ],
                            'default'   => '1',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_nested_icon_dropdown_arrow' ] => 'order: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'icon_dropdown_padding_nested_level',
                        [
                            'label'      => esc_html__( 'Padding', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_nested_icon_dropdown_arrow' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'icon_dropdown_margin_nested_level',
                        [
                            'label'      => esc_html__( 'Margin', 'portuna-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default'    => [
                                'top'    => '0',
                                'right'  => '5',
                                'bottom' => '0',
                                'left'   => '5',
                                'unit'   => 'px'
                            ],
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_nested_icon_dropdown_arrow' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'menu_icon_indicator_heading_first_lvl',
                [
                    'label'     => esc_html__( 'Icon Dropdown Style (First Level)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->start_controls_tabs( 'icon_dropdown_first_lvl' );

                $this->start_controls_tab(
                    'icon_dropdown_normal_first_lvl',
                    [
                        'label' => esc_html__( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_dropdown_color_first_lvl',
                        [
                            'label'     => esc_html__( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '#000000',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_top_icon_dropdown_arrow' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_dropdown_bgcolor_first_lvl',
                        [
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_top_icon_dropdown_arrow' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'icon_dropdown_hover_first_lvl',
                    [
                        'label' => esc_html__( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_dropdown_hover_color_first_lvl',
                        [
                            'label'     => esc_html__( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_top_icon_dropdown_arrow_hover' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_dropdown_hover_bgcolor_first_lvl',
                        [
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_top_icon_dropdown_arrow_hover' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'menu_icon_indicator_heading_nested_lvl',
                [
                    'label'     => esc_html__( 'Icon Dropdown Style (Nested Level)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->start_controls_tabs( 'icon_indicator_effects_nested_lvl' );

                $this->start_controls_tab(
                    'icon_indicator_item_normal_nested_lvl',
                    [
                        'label' => esc_html__( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_indicator_item_color_nested_lvl',
                        [
                            'label'     => esc_html__( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_nested_icon_dropdown_arrow' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_indicator_item_bgcolor_nested_lvl',
                        [
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_nested_icon_dropdown_arrow' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'icon_indicator_hover_nested_lvl',
                    [
                        'label' => esc_html__( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_indicator_item_color_hover_nested_lvl',
                        [
                            'label'     => esc_html__( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_nested_icon_dropdown_arrow_hover' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'icon_indicator_item_bgcolor_hover_nested_lvl',
                        [
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_menu_nested_icon_dropdown_arrow_hover' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    // Icon Item
    public function style_IconItem() {

        $this->start_controls_section(
            'section_icon_item_style',
            [
                'label' => esc_html__( 'Icon Item', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'section_icon_item_warning',
                [
                    'raw' 		      => sprintf(
                        '%s <a href="%s" target="_blank">%s</a>',
                        esc_html__( 'Set your Icon type in the Item -', 'portuna-addon' ),
                        admin_url( 'nav-menus.php' ),
                        esc_html__( 'Appearance > Menus', 'portuna-addon' ),
                    ),
                    'type' 		      => Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'render_type' 	  => 'ui',
                ]
            );

            $this->start_controls_tabs( 'icon_group' );

                $this->start_controls_tab(
                    'icon_first_level',
                    [
                        'label' => esc_html__( 'First Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'menu_icon_heading_first_level',
                        [
                            'label'     => esc_html__( 'First Level', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_icon_size_first_level',
                        [
                            'label' => esc_html__( 'Icon Size', 'portuna-addon' ),
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
                            'label'     => esc_html__( 'Icon Position', 'portuna-addon' ),
                            'type'      => Controls_Manager::CHOOSE,
                            'toggle'    => false,
                            'options'   => [
                                '-1'     => [
                                    'title'  => esc_html__( 'Left', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-left',
                                ],
                                '1'       => [
                                    'title'  => esc_html__( 'Right', 'portuna-addon' ),
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
                            'label'      => esc_html__( 'Padding', 'portuna-addon' ),
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
                            'label'      => esc_html__( 'Margin', 'portuna-addon' ),
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
                        'label' => esc_html__( 'Nested Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'submenu_icon_heading_nested_level',
                        [
                            'label'     => esc_html__( 'Nested Level', 'portuna-addon' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before'
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_icon_size_nested_level',
                        [
                            'label' => esc_html__( 'Icon Size', 'portuna-addon' ),
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
                            'label'     => esc_html__( 'Icon Position', 'portuna-addon' ),
                            'type'      => Controls_Manager::CHOOSE,
                            'toggle'    => false,
                            'options'   => [
                                '1'       => [
                                    'title'  => esc_html__( 'Right', 'portuna-addon' ),
                                    'icon'   => 'eicon-h-align-right',
                                ],
                                '-1'     => [
                                    'title'  => esc_html__( 'Left', 'portuna-addon' ),
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
                            'label'      => esc_html__( 'Padding', 'portuna-addon' ),
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
                            'label'      => esc_html__( 'Margin', 'portuna-addon' ),
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
                    'label'     => esc_html__( 'Icon Style (First Level)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

                $this->start_controls_tabs( 'icon_effects_first_lvl' );

                $this->start_controls_tab(
                    'icon_normal_first_lvl',
                    [
                        'label' => esc_html__( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_color_first_lvl',
                        [
                            'label'     => esc_html__( 'Color', 'portuna-addon' ),
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
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
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
                        'label' => esc_html__( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_color_hover_first_lvl',
                        [
                            'label'     => esc_html__( 'Color', 'portuna-addon' ),
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
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
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
                    'label'     => esc_html__( 'Icon Style (Nested Level)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->start_controls_tabs( 'icon_effects_nested_lvl' );

                $this->start_controls_tab(
                    'icon_normal_nested_lvl',
                    [
                        'label' => esc_html__( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_color_nested_lvl',
                        [
                            'label'     => esc_html__( 'Color', 'portuna-addon' ),
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
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
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
                        'label' => esc_html__( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'icon_color_hover_nested_lvl',
                        [
                            'label'     => esc_html__( 'Color', 'portuna-addon' ),
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
                            'label'     => esc_html__( 'Background Color', 'portuna-addon' ),
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

    // Badge Item
    public function style_BadgeItem() {

        $this->start_controls_section(
            'section_badge_item_style',
            [
                'label' => esc_html__( 'Badge Item', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'section_badge_warning',
                [
                    'raw'             => sprintf(
                        '%s <a href="%s" target="_blank">%s</a>',
                        esc_html__( 'Set your Badge text in the Item -', 'portuna-addon' ),
                        admin_url( 'nav-menus.php' ),
                        esc_html__( 'Appearance > Menus', 'portuna-addon' )
                    ),
                    'type' 		      => Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'render_type' 	  => 'ui',
                ]
            );

            $this->start_controls_tabs( 'bagde_group' );

                $this->start_controls_tab(
                    'badge_first_level',
                    [
                        'label' => esc_html__( 'First Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'label'    => esc_html__( 'Badge Background', 'portuna-addon' ),
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
                            'label'    => esc_html__( 'Badge Box Shadow', 'portuna-addon' ),
                            'name'     => 'badge_shadow_first_lvl',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ],
                        ]
                    );

                    $this->add_control(
                        'badge_text_color_first_lvl',
                        [
                            'label'     => esc_html__( 'Text Color', 'portuna-addon' ),
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
                            'label'      => esc_html__( 'Padding', 'portuna-addon' ),
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
                            'label'      => esc_html__( 'Margin', 'portuna-addon' ),
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
                            'label'     => esc_html__( 'Border Type', 'portuna-addon' ),
                            'type'      => Controls_Manager::SELECT,
                            'options'   => [
                                'none'   => esc_html__( 'None', 'portuna-addon' ),
                                'solid'  => esc_html__( 'Solid', 'portuna-addon' ),
                                'double' => esc_html__( 'Double', 'portuna-addon' ),
                                'dotted' => esc_html__( 'Dotted', 'portuna-addon' ),
                                'dashed' => esc_html__( 'Dashed', 'portuna-addon' ),
                                'groove' => esc_html__( 'Groove', 'portuna-addon' ),
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
                        'label' => esc_html__( 'Nested Level', 'portuna-addon' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'label'    => esc_html__( 'Badge Background', 'portuna-addon' ),
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
                            'label'    => esc_html__( 'Badge Box Shadow', 'portuna-addon' ),
                            'name'     => 'badge_shadow_nested_lvl',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_menu' ],
                        ]
                    );

                    $this->add_control(
                        'badge_text_color_nested_lvl',
                        [
                            'label'     => esc_html__( 'Text Color', 'portuna-addon' ),
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
                            'label'      => esc_html__( 'Padding', 'portuna-addon' ),
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
                            'label'      => esc_html__( 'Margin', 'portuna-addon' ),
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
                            'label'     => esc_html__( 'Border Type', 'portuna-addon' ),
                            'type'      => Controls_Manager::SELECT,
                            'options'   => [
                                'none'   => esc_html__( 'None', 'portuna-addon' ),
                                'solid'  => esc_html__( 'Solid', 'portuna-addon' ),
                                'double' => esc_html__( 'Double', 'portuna-addon' ),
                                'dotted' => esc_html__( 'Dotted', 'portuna-addon' ),
                                'dashed' => esc_html__( 'Dashed', 'portuna-addon' ),
                                'groove' => esc_html__( 'Groove', 'portuna-addon' ),
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
                    'label'     => esc_html__( 'Badge General', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label'           => esc_html__( 'Badge Typography', 'portuna-addon' ),
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

    // Hamburger Options
    public function style_HamburgerOptions() {

        $this->start_controls_section(
            'section_hamburger_style',
            [
                'label' => esc_html__( 'Hamburger Options', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'device_general',
                [
                    'label'     => esc_html__( 'Breakpoint Options', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'device_breakpoint_hamburger',
                [
                    'label'        => esc_html__( 'Enable Breakpoint?', 'portuna-addon' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => 'yes',
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'device_breakpoint_responsive',
                [
                    'label'        => esc_html__( 'Breakpoint', 'portuna-addon' ),
                    'type'         => Controls_Manager::SELECT,
                    'default'      => 'mobile',
                    'prefix_class' => 'portuna-addon-menu-breakpoint--',
                    'options'      => [
                        'tablet'    => esc_html__( 'Tablet (< 1025px)', 'portuna-addon' ),
                        'mobile'    => esc_html__( 'Mobile (< 768px)', 'portuna-addon' ),
                        'custom'    => esc_html__( 'Custom (<)', 'portuna-addon' ),
                    ],
                    'condition'    => [
                        'device_breakpoint_hamburger' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'device_breakpoint_customize_responsive',
                [
                    'label'              => esc_html__( 'Custom Number (< px)', 'portuna-addon' ),
                    'type'               => Controls_Manager::NUMBER,
                    'default'            => 1000,
                    'min'                => 1,
                    'required'           => true,
                    'selectors'          => [
                        '{{WRAPPER}}' => '--breakpoint: {{VALUE}}px;',
                    ],
                    'description'        => esc_html__( 'Set your responsive number (px) on which the hamburger menu should appear.', 'portuna-addon' ),
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
                    'label'              => esc_html__( 'Full Width', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'description'        => esc_html__( 'Stretch the dropdown of the menu to full width.', 'portuna-addon' ),
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
                    'label'   => esc_html__( 'Toggle Align', 'portuna-addon' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'default' => 'center',
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'portuna-addon' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'portuna-addon' ),
                            'icon'  => 'eicon-h-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'portuna-addon' ),
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
                    'label'      => esc_html__( 'Padding', 'portuna-addon' ),
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
                    'label'      => esc_html__( 'Margin', 'portuna-addon' ),
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
                    'label'     => esc_html__( 'Border Type', 'portuna-addon' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => [
                        'none'   => esc_html__( 'None', 'portuna-addon' ),
                        'solid'  => esc_html__( 'Solid', 'portuna-addon' ),
                        'double' => esc_html__( 'Double', 'portuna-addon' ),
                        'dotted' => esc_html__( 'Dotted', 'portuna-addon' ),
                        'dashed' => esc_html__( 'Dashed', 'portuna-addon' ),
                        'groove' => esc_html__( 'Groove', 'portuna-addon' ),
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
                'hamburger_toggle_states',
                [
                    'label'     => esc_html__( 'Hamburger (Toggle)', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'device_breakpoint_hamburger'  => 'yes',
                    ],
                ]
            );

            $this->start_controls_tabs( 'hamburger_toggle_tabs' );

                $this->start_controls_tab(
                    'hamburger_open',
                    [
                        'label'     => esc_html__( 'Open', 'portuna-addon' ),
                        'condition' => [
                            'device_breakpoint_hamburger'  => 'yes',
                        ],
                    ]
                );

                    $this->add_control(
                        'hamburger_open_icons',
                        [
                            'label'            => esc_html__( 'Open Icon', 'portuna-addon' ),
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

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'hamburger_close',
                    [
                        'label'     => esc_html__( 'Close', 'portuna-addon' ),
                        'condition' => [
                            'device_breakpoint_hamburger'  => 'yes',
                        ],
                    ]
                );

                    $this->add_control(
                        'hamburger_close_icons',
                        [
                            'label'            => esc_html__( 'Close Icon', 'portuna-addon' ),
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

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->start_controls_tabs( 'hamburger_group' );

                $this->start_controls_tab(
                    'hamburger_normal',
                    [
                        'label'     => esc_html__( 'Normal', 'portuna-addon' ),
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
                        'label'     => esc_html__( 'Hover', 'portuna-addon' ),
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
                        'label'     => esc_html__( 'Active', 'portuna-addon' ),
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