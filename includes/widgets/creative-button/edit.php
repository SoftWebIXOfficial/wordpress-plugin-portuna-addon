<?php

namespace PortunaAddon\Widgets\Edit;

defined( 'ABSPATH' ) || exit;

use \Elementor\Utils;
use \Elementor\Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Text_Shadow;

use \PortunaAddon\Widgets\Portuna_Widget_Base;
use \PortunaAddon\Helpers\ControlsManager;

class CreativeButton extends Portuna_Widget_Base {
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'creative-button-style-layout1',
            plugin_dir_url( dirname( __FILE__ ) ) . 'creative-button/assets/css/layout1.min.css',
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

    /**
     * Map the widget controls.
     * Used to allow the user to customize items.
     */
    private static $css_map = [
        'wrap_button_content'           => ' .portuna-addon--creative-button-content .portuna-addon--creative-button-link',
        'wrap_button_content_hover'     => ' .portuna-addon--creative-button-content .portuna-addon--creative-button-link:hover',
        'wrap_button_icon'              => ' .portuna-addon--creative-button-content .portuna-addon--creative-button-icon',
        'wrap_button_icon_hover'        => ' .portuna-addon--creative-button-content .portuna-addon--creative-button-icon:hover',
        'wrap_button_icon_svg'          => ' .portuna-addon--creative-button-content .portuna-addon--creative-button-link > svg, .portuna-addon--creative-button-content .portuna-addon--creative-button-link > svg g, .portuna-addon--creative-button-content .portuna-addon--creative-button-link > svg path',
        'wrap_button_icon_svg_hover'    => ' .portuna-addon--creative-button-content .portuna-addon--creative-button-link:hover > svg, .portuna-addon--creative-button-content .portuna-addon--creative-button-link:hover > svg g, .portuna-addon--creative-button-content .portuna-addon--creative-button-link:hover > svg path',
        'wrap_button_text'              => ' .portuna-addon--creative-button-content .portuna-addon--creative-button-link',
        'wrap_button_text_hover'        => ' .portuna-addon--creative-button-content .portuna-addon--creative-button-link:hover',
        'wrap_button_border_unique'     => ' .portuna-addon--creative-button-link.portuna-addon--creative-button-link--unique::before, .portuna-addon--creative-button-link.portuna-addon--creative-button-link--unique::after',
        'wrap_button_border_unique_hover' => ' .portuna-addon--creative-button-link.portuna-addon--creative-button-link--unique:hover::before, .portuna-addon--creative-button-link.portuna-addon--creative-button-link--unique:hover::after',
    ];

    public function get_style_depends() {
        return [ 'creative-button-style-layout1' ];
    }

//     public function get_script_depends() {
//         return [ 'jquery', 'mega-menu-script-layout1' ];
//     }

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
        return 'portuna-addon-creative-button';
    }

    /**
     * Widget Title – The get_title() method, which again, is a very simple one,
     * you need to return the widget title that will be displayed as the widget label.
     */
    public function get_title() {
        return __( 'Creative Button', 'portuna-addon' );
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
        return [ 'button', 'creative-button', 'link' ];
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
    }

    public function settings_section1() {

        $this->start_controls_section(
            'section_content_menu',
            [
                'label' => __( 'Settings', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'section_template_style',
                [
                    'label'       => esc_html__( 'Choose Style', 'portuna-addon' ),
                    'type'        => ControlsManager::SELECT_IMAGE,
                    'description' => esc_html__( 'Unique style under the theme portuna', 'portuna-addon' ),
                    'default'     => 'view',
                    'options'     => [
                        'view'           => [
                            'title'      => esc_html__( 'Button Style 1', 'portuna-addon' ),
                            'url'        => Utils::get_placeholder_image_src(),
                        ],
                        'view_template1' => [
                            'title'      => esc_html__( 'Button Style 2', 'portuna-addon' ),
                            'url'        => Utils::get_placeholder_image_src(),
                        ],
                    ],
                ]
            );

            $this->add_control(
                'button_label_text',
                [
                    'label'       => esc_html__( 'Label Name', 'portuna-addon' ),
                    'type'        => Controls_Manager::TEXT,
                    'default'     => esc_html__( 'Learn More', 'portuna-addon' ),
                    'placeholder' => esc_html__( 'Learn More', 'portuna-addon' ),
                    'dynamic'     => [
                        'active'  => true,
                    ],
                ]
            );

            $this->add_control(
                'button_url',
                [
                    'label'       => esc_html__( 'URL', 'portuna-addon' ),
                    'type'        => Controls_Manager::URL,
                    'placeholder' => esc_url( 'https://example.com' ),
                    'dynamic'     => [
                        'active'  => true,
                    ],
                    'default'     => [
                        'url'     => '#'
                    ]
                ]
            );

            $this->add_responsive_control(
                'button_alignment',
                [
                    'label'                => esc_html__( 'Button Alignment', 'portuna-addon' ),
                    'type'                 => Controls_Manager::CHOOSE,
                    'options'              => [
                        'left'             => [
                            'title'        => __( 'Left', 'portuna-addon' ),
                            'icon'         => 'eicon-text-align-left',
                        ],
                        'center'           => [
                            'title'        => __( 'Center', 'portuna-addon' ),
                            'icon'         => 'eicon-text-align-center',
                        ],
                        'right'            => [
                            'title'        => __( 'Right', 'portuna-addon' ),
                            'icon'         => 'eicon-text-align-right',
                        ],
                        'justified'            => [
                            'title'        => __( 'Justified', 'portuna-addon' ),
                            'icon'         => 'eicon-text-align-justify',
                        ]
                    ],
                    'selectors_dictionary' => [
                        'left'      => 'margin-right: auto;',
                        'center'    => 'margin-left: auto; margin-right: auto;',
                        'right'     => 'margin-left: auto;',
                        'justified' => 'max-width: 100%; margin-left: auto; margin-right: auto;',
                    ],
                    'default'              => 'center',
                    'selectors'            => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_button_text' ] => '{{VALUE}}',
                    ]
                ]
            );

            $this->add_control(
                'button_id',
                [
                    'label'       => esc_html__( 'Button ID', 'portuna-addon' ),
                    'type'        => Controls_Manager::TEXT,
                    'dynamic'     => [
                        'active'  => true,
                    ],
                    'default'     => ''
                ]
            );

        $this->end_controls_section();

    }

    public function style_section1() {
        $this->start_controls_section(
            'button_general_style',
            [
                'label' => __( 'General Options', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label'           => __( 'Typography', 'portuna-addon' ),
                    'name'            => 'button_typography',
                    'selector'        => '{{WRAPPER}}' . self::$css_map[ 'wrap_button_text' ],
                    'fields_options'  => [
                        'font_weight' => [
                            'default' => '400'
                        ]
                    ]
                ]
            );

            $this->add_responsive_control(
                'button_padding',
                [
                    'label'      => __( 'Padding', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_button_text' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                    'default'    => [
                        'top'    => '18',
                        'right'  => '56',
                        'bottom' => '18',
                        'left'   => '56',
                        'unit'   => 'px'
                    ],
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'button_border_type',
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
                        'unique' => __( 'Unique Portuna', 'portuna-addon' ),
                    ],
                    'frontend_available' => true,
                    'default'            => 'none',
                ]
            );

            $this->add_responsive_control(
                'button_border_width',
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
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_button_content' ]       => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_button_border_unique' ] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'button_border_type!' => 'none',
                    ],
                ]
            );

            $this->add_responsive_control(
                'submenu_radius_panel',
                [
                    'label'      => esc_html__( 'Border Radius', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_button_content' ]       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition'  => [
                        'button_border_type!' => 'unique'
                    ]
                ]
            );

            $this->start_controls_tabs( 'button_effect' );

                $this->start_controls_tab(
                    'button_normal',
                    [
                        'label' => __( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'button_text_color_normal',
                        [
                            'label'     => __( 'Text Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_text' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_bg_color_normal',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_content' ] => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_border_color_normal',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_content' ]       => 'border-color: {{VALUE}};',
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_border_unique' ] => 'border-color: {{VALUE}}!important;',
                            ],
                            'condition' => [
                                'button_border_type!' => 'none',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Text_Shadow::get_type(),
                        [
                            'label'    => __( 'Label Text Shadow', 'portuna-addon' ),
                            'name'     => 'button_label_normal',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_button_text' ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'label'    => __( 'Box Shadow', 'portuna-addon' ),
                            'name'     => 'button_box_shadow_normal',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_button_content' ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'button_hover',
                    [
                        'label' => __( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'button_color_hover',
                        [
                            'label'     => __( 'Text Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_text_hover' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_border_color_hover',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_content_hover' ]       => 'border-color: {{VALUE}};',
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_border_unique_hover' ] => 'border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'button_border_type!' => 'none',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_bg_color_hover',
                        [
                            'label'     => __( 'Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_content_hover' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Text_Shadow::get_type(),
                        [
                            'label'    => __( 'Label Text Shadow', 'portuna-addon' ),
                            'name'     => 'button_label_hover',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_button_text_hover' ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'label'    => __( 'Box Shadow', 'portuna-addon' ),
                            'name'     => 'button_box_shadow_hover',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_button_content_hover' ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function style_section2() {
        $this->start_controls_section(
            'button_icon_style',
            [
                'label' => __( 'Icon', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'button_enable_icon',
                [
                    'label'        => __( 'Enable Icon?', 'portuna-addon' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => 'yes',
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'button_icons',
                [
                    'label'            => __( 'Icon', 'portuna-addon' ),
                    'type'             => Controls_Manager::ICONS,
                    'fa4compatibility' => 'button_icon',
                    'default'          => [
                        'value'        => 'fas fa-bars',
                        'library'      => 'fa-solid',
                    ],
                    'condition' => [
                        'button_enable_icon'  => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'button_icon_size',
                [
                    'label'              => __( 'Icon Size (px)', 'portuna-addon' ),
                    'type'               => Controls_Manager::NUMBER,
                    'default'            => 14,
                    'min'                => 4,
                    'required'           => true,
                    'frontend_available' => true,
                    'selectors'          => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_button_icon' ]     => 'font-size: {{VALUE}}px',
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_button_icon_svg' ] => 'width: {{VALUE}}px; height: {{VALUE}}px',
                    ],
                    'condition'          => [
                        'button_enable_icon'  => 'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'button_icon_pos',
                [
                    'label'     => __( 'Icon Position', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'toggle'    => false,
                    'default'   => 'row',
                    'options'   => [
                        'row'         => [
                            'title'   => __( 'Left', 'portuna-addon' ),
                            'icon'    => 'eicon-long-arrow-left',
                        ],
                        'row-reverse' => [
                            'title'   => __( 'Right', 'portuna-addon' ),
                            'icon'    => 'eicon-long-arrow-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_button_text' ] => 'flex-direction: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'button_icon_padding',
                [
                    'label'      => __( 'Padding', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_button_icon' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                    'separator' => 'before'
                ]
            );

            $this->start_controls_tabs( 'button_icon_effect' );

                $this->start_controls_tab(
                    'button_icon_normal',
                    [
                        'label' => __( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'button_icon_color_normal',
                        [
                            'label'     => __( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_icon' ]     => 'color: {{VALUE}};',
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_icon_svg' ] => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'button_icon_hover',
                    [
                        'label' => __( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'button_icon_color_hover',
                        [
                            'label'     => __( 'Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_icon_hover' ]     => 'color: {{VALUE}};',
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_button_icon_svg_hover' ] => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Render Frontend Output – The render() method, which is where you actually
     * render the code and generate the final HTML on the frontend using PHP.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        extract( $settings );

        require $this->side_render_dir() . '/' . $section_template_style . '.php';
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new CreativeButton() );