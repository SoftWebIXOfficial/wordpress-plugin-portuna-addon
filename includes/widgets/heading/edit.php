<?php

namespace PortunaAddon\Widgets\Edit;

defined( 'ABSPATH' ) || exit;

use \Elementor\Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Text_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Border;

use \PortunaAddon\Widgets\Portuna_Widget_Base;

class AdvancedHeading extends Portuna_Widget_Base {
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $this->widgets_enqueue_styles();
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'widgets_enqueue_scripts' ] );
    }

    public function widgets_enqueue_styles() {
        // Styles enqueue.
        wp_enqueue_style(
            'advanced-heading-style-layout1',
            plugin_dir_url( dirname( __FILE__ ) ) . 'heading/assets/css/layout1.min.css',
            [],
            null
        );
    }

    public function widgets_enqueue_scripts() {
        // Scripts enqueue.
        wp_enqueue_script(
            'advanced-heading-script-layout1',
            plugin_dir_url( dirname( __FILE__ ) ) . 'heading/assets/js/layout1.min.js',
            [ 'jquery' ],
            null
        );
    }

    /**
     * Map the widget controls.
     * Used to allow the user to customize items.
     */
    private static $css_map = [
        'wrap_content'                              => ' .portuna-addon--advanced-heading--content',
        'wrap_content_title'                        => ' .portuna-addon--advanced-heading--content-title',
        'wrap_content_subtitle'                     => ' .portuna-addon--advanced-heading--content-subtitle',
        'wrap_content_title_hover'                  => ' .portuna-addon--advanced-heading--content-title:hover',
        'wrap_content_subtitle_hover'               => ' .portuna-addon--advanced-heading--content-subtitle:hover',
        'wrap_content_subtitle_square'              => ' .portuna-addon--advanced-heading--content-subtitle-square',
        'wrap_content_subtitle_square_before'       => ' .portuna-addon--advanced-heading--content-subtitle-square::before',
        'wrap_content_subtitle_square_hover'        => ' .portuna-addon--advanced-heading--content-subtitle:hover .portuna-addon--advanced-heading--content-subtitle-square',
        'wrap_content_subtitle_square_before_hover' => ' .portuna-addon--advanced-heading--content-subtitle:hover .portuna-addon--advanced-heading--content-subtitle-square::before',
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
        return 'portuna_addon_advanced_heading';
    }

    public function get_title() {
        return __( 'Advanced Heading', 'portuna-addon' );
    }

    public function get_icon() {
        return 'sm sm-accordion';
    }

    public function get_categories() {
        return [ 'portuna-addons-category' ];
    }

    protected function _register_controls() {
        // Initialize contents tab.
        $this->content_section1();

        $this->style_section1();
        $this->style_section2();
    }

    public function content_section1() {

        $this->start_controls_section(
            'portuna_settings',
            [
                'label' => __( 'Settings Content', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_responsive_control(
                'portuna_title_subtitle_alignment',
                [
                    'label'     => __( 'Title & Subtitle Alignment', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'toggle'    => false,
                    'options'   => [
                        'flex-start'   => [
                            'title' => __( 'Left', 'portuna-addon' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'portuna-addon' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'flex-end'  => [
                            'title' => __( 'Right', 'portuna-addon' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'default'   => 'center',
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_content_title' ]    => 'justify-content: {{VALUE}}',
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle' ] => 'justify-content: {{VALUE}}',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms'    => [
                            [
                                'name'     => 'portuna_title_switch',
                                'operator' => '===',
                                'value'    => 'yes',
                            ],
                            [
                                'name'     => 'portuna_subtitle_switch',
                                'operator' => '===',
                                'value'    => 'yes',
                            ],
                        ],
                    ],
                    'separator' => 'after'
                ]
            );

            $this->add_control(
                'portuna_name_section',
                [
                    'label'     => __( 'Heading', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'portuna_title_switch',
                [
                    'label'        => __( 'Enable title?', 'portuna-addon' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => 'yes',
                    'return_value' => 'yes',
                    'separator'    => 'before',
                ]
            );

            $this->add_control(
                'portuna_title',
                [
                    'label'     => __( 'Title', 'portuna-addon' ),
                    'type'      => Controls_Manager::TEXTAREA,
                    'default'   => __( 'Set Your Title', 'portuna-addon' ),
                    'condition' => [
                        'portuna_title_switch' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'portuna_title_tag',
                [
                    'label'     => __( 'Title HTML Tag', 'portuna-addon' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => [
                        'h1'   => __( 'H1', 'portuna-addon' ),
                        'h2'   => __( 'H2', 'portuna-addon' ),
                        'h3'   => __( 'H3', 'portuna-addon' ),
                        'h4'   => __( 'H4', 'portuna-addon' ),
                        'h5'   => __( 'H5', 'portuna-addon' ),
                        'h6'   => __( 'H6', 'portuna-addon' ),
                        'div'  => __( 'div', 'portuna-addon' ),
                        'span' => __( 'span', 'portuna-addon' ),
                        'p'    => __( 'p', 'portuna-addon' ),
                    ],
                    'default'   => 'h2',
                    'condition' => [
                        'portuna_title_switch' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'portuna_title_link',
                [
                    'label'       => __( 'Title Link', 'portuna-addon' ),
                    'type'        => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'portuna-addon' ),
                    'show_label'  => true,
                    'dynamic'     => [
                        'active' => true,
                    ],
                    'condition' => [
                        'portuna_title_switch' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'portuna_name_subtitle',
                [
                    'label'     => __( 'Subtitle', 'portuna-addon' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'portuna_subtitle_switch',
                [
                    'label'        => __( 'Enable subtitle?', 'portuna-addon' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => 'yes',
                    'return_value' => 'yes',
                    'separator'    => 'before',
                ]
            );

            $this->add_control(
                'portuna_subtitle_position',
                [
                    'label'     => __( 'Subtitle position', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'options'   => [
                        'column-reverse'   => [
                            'title' => __( 'Top', 'portuna-addon' ),
                            'icon'  => 'eicon-v-align-top',
                        ],
                        'column' => [
                            'title' => __( 'Bottom', 'portuna-addon' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ]
                    ],
                    'default'   => 'column-reverse',
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_content' ] => 'flex-direction: {{VALUE}}',
                    ],
                    'condition' => [
                        'portuna_subtitle_switch' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'portuna_subtitle',
                [
                    'label'     => __( 'Subtitle', 'portuna-addon' ),
                    'type'      => Controls_Manager::TEXTAREA,
                    'default'   => __( 'Set Your Subtitle', 'portuna-addon' ),
                    'condition' => [
                        'portuna_subtitle_switch' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'portuna_subtitle_tag',
                [
                    'label'     => __( 'Subtitle HTML Tag', 'portuna-addon' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => [
                        'h2'   => __( 'H2', 'portuna-addon' ),
                        'h3'   => __( 'H3', 'portuna-addon' ),
                        'h4'   => __( 'H4', 'portuna-addon' ),
                        'h5'   => __( 'H5', 'portuna-addon' ),
                        'h6'   => __( 'H6', 'portuna-addon' ),
                        'div'  => __( 'div', 'portuna-addon' ),
                        'span' => __( 'span', 'portuna-addon' ),
                        'p'    => __( 'p', 'portuna-addon' ),
                    ],
                    'default'   => 'h6',
                    'condition' => [
                        'portuna_subtitle_switch' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'portuna_subtitle_link',
                [
                    'label'       => __( 'Subtitle Link', 'portuna-addon' ),
                    'type'        => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'portuna-addon' ),
                    'show_label'  => true,
                    'dynamic'     => [
                        'active'  => true,
                    ],
                    'condition'   => [
                        'portuna_subtitle_switch' => 'yes',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    public function style_section1() {

        $this->start_controls_section(
            'portuna_style',
            [
                'label'     => __( 'Title Style', 'portuna-addon' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'portuna_title_switch' => 'yes',
                ],
            ]
        );
        
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label'           => __( 'Title Typography', 'portuna-addon' ),
                    'name'            => 'portuna_title_typography',
                    'selector'        => '{{WRAPPER}}' . self::$css_map[ 'wrap_content_title' ],
                    'fields_options'  => [
                        'font_weight' => [
                            'default' => '400'
                        ]
                    ]
                ]
            );

            $this->add_responsive_control(
                'portuna_title_distance',
                [
                    'label' => __( 'Title Distance', 'simpli' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'size'  => '15',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_content_title' ] => 'margin: {{SIZE}}{{UNIT}} 0;',
                    ],
                ]
            );

            $this->start_controls_tabs( 'items_effects' );

                $this->start_controls_tab(
                    'portuna_title_normal',
                    [
                        'label' => __( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'portuna_title_color',
                        [
                            'label'     => __( 'Title Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_content_title' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    
                    $this->add_group_control(
                        Group_Control_Text_Shadow::get_type(),
                        [
                            'label'    => __( 'Title Text Shadow', 'portuna-addon' ),
                            'name'     => 'portuna_title_shadow',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_content_title' ],
                        ]
                    );

                $this->end_controls_tab();
                
                $this->start_controls_tab(
                    'portuna_hover',
                    [
                        'label' => __( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'portuna_title_color_hover',
                        [
                            'label'     => __( 'Title Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_content_title_hover' ] => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Text_Shadow::get_type(),
                        [
                            'label'    => __( 'Title Text Shadow', 'portuna-addon' ),
                            'name'     => 'portuna_title_shadow_hover',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_content_title_hover' ],
                        ]
                    );

                $this->end_controls_tab();
                
            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    public function style_section2() {

        $this->start_controls_section(
            'portuna_style2',
            [
                'label'     => __( 'Subtitle Style', 'portuna-addon' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'portuna_subtitle_switch' => 'yes',
                ],
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label'           => __( 'Subtitle Typography', 'portuna-addon' ),
                    'name'            => 'portuna_subtitle_typography',
                    'selector'        => '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle' ],
                    'fields_options'  => [
                        'font_weight' => [
                            'default' => '400'
                        ]
                    ]
                ]
            );

            $this->add_responsive_control(
                'portuna_subtitle_padding',
                [
                    'label'      => __( 'Subtitle Padding', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default'    => [
                        'top'    => '7',
                        'right'  => '32',
                        'bottom' => '7',
                        'left'   => '32'
                    ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                    'condition' => [
                        'portuna_addon_items_decor_type' => 'shadow_square',
                    ],
                    'separator' => 'before'
                ]
            );
            
            $this->add_control(
                'portuna_subtitle_border_type',
                [
                    'label'     => __( 'Border Type', 'simpli' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => [
                        'none'   => __( 'None', 'simpli' ),
                        'solid'  => __( 'Solid', 'simpli' ),
                        'double' => __( 'Double', 'simpli' ),
                        'dotted' => __( 'Dotted', 'simpli' ),
                        'dashed' => __( 'Dashed', 'simpli' ),
                        'groove' => __( 'Groove', 'simpli' ),
                    ],
                    'default'   => 'solid',
                    'selectors' => [
                        '{{SELECTOR}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-style: {{VALUE}};',
                    ],
                    'condition' => [
                        'portuna_addon_items_decor_type' => 'shadow_square',
                    ],
                ]
            );

            $this->add_responsive_control(
                'portuna_subtitle_border_width',
                [
                    'label'      => esc_html__( 'Border Width', 'simpli' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'default'    => [
                        'top'    => '1',
                        'right'  => '1',
                        'bottom' => '1',
                        'left'   => '1'
                    ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'responsive' => true,
                    'condition' => [
                        'portuna_subtitle_border_type!' => 'none',
                    ],
                    'conditions' => [
                        'terms'    => [
                            [
                                'name'     => 'portuna_addon_items_decor_type',
                                'operator' => '===',
                                'value'    => 'shadow_square',
                            ],
                        ],
                    ],
                ]
            );

            $this->add_responsive_control(
                'portuna_subtitle_border_radius',
                [
                    'label'      => esc_html__( 'Border Radius', 'simpli' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'portuna_subtitle_border_type!' => 'none',
                    ],
                    'conditions' => [
                        'terms'    => [
                            [
                                'name'     => 'portuna_addon_items_decor_type',
                                'operator' => '===',
                                'value'    => 'shadow_square',
                            ],
                        ],
                    ],
                ]
            );

            $this->add_control(
                'portuna_addon_items_decor_type',
                [
                    'label'     => __( 'Decoration Type', 'portuna-addon' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => [
                        ''               => __( 'None', 'portuna-addon' ),
                        'shadow_square'  => __( 'Shadow Square', 'portuna-addon' )
                    ],
                    'default'   => 'shadow_square',
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'portuna_subtitle_square_hr_position',
                [
                    'label'     => __( 'Square Position Horizontal', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'toggle'    => false,
                    'options'   => [
                        'left'   => [
                            'title' => __( 'Left', 'portuna-addon' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'portuna-addon' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                    ],
                    'default'   => 'right',
                    'condition' => [
                        'portuna_addon_items_decor_type' => 'shadow_square',
                    ],
                ]
            );

            $this->add_control(
                'portuna_subtitle_square_vr_position',
                [
                    'label'     => __( 'Square Position Vertical', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'toggle'    => false,
                    'options'   => [
                        'bottom'   => [
                            'title' => __( 'Bottom', 'portuna-addon' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                        'top'   => [
                            'title' => __( 'Top', 'portuna-addon' ),
                            'icon'  => 'eicon-v-align-top',
                        ],
                    ],
                    'default'   => 'bottom',
                    'condition' => [
                        'portuna_addon_items_decor_type' => 'shadow_square',
                    ],
                ]
            );

            $this->add_control(
                'portuna_subtitle_decor_bgcolor',
                [
                    'label'     => __( 'Square Background Color', 'portuna-addon' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#212529',
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square_before' ] => 'background-color: {{VALUE}};'
                    ],
                    'condition' => [
                        'portuna_addon_items_decor_type' => 'shadow_square',
                    ],
                ]
            );

            $this->add_control(
                'portuna_subtitle_animation_transition',
                [
                    'label'     => __( 'Animation Duration', 'portuna-addon' ),
                    'type'      => Controls_Manager::SLIDER,
                    'default'   => [
                        'size' => 0.3,
                    ],
                    'range'     => [
                        'px'   => [
                            'max'  => 3,
                            'step' => 0.1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ]        => 'transition-duration: {{SIZE}}s',
                        '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square_before' ] => 'transition-duration: {{SIZE}}s',
                    ],
                    'separator' => 'before'
                ]
            );

            $this->start_controls_tabs( 'subtitle_effects' );

                $this->start_controls_tab(
                    'portuna_subtitle_normal',
                    [
                        'label' => __( 'Normal', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'portuna_subtitle_color',
                        [
                            'label'     => __( 'Subtitle Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle' ] => 'color: {{VALUE}};'
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Text_Shadow::get_type(),
                        [
                            'label'    => __( 'Subtitle Text Shadow', 'portuna-addon' ),
                            'name'     => 'portuna_subtitle_shadow',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle' ],
                        ]
                    );

                    $this->add_control(
                        'portuna_subtitle_bgcolor',
                        [
                            'label'     => __( 'Subtitle Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '#FFFFFF',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'background-color: {{VALUE}};'
                            ],
                            'condition' => [
                                'portuna_addon_items_decor_type' => 'shadow_square',
                            ],
                        ]
                    );

                    $this->add_control(
                        'portuna_subtitle_border_color',
                        [
                            'label'      => esc_html__( 'Border Color', 'simpli' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '#212529',
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square' ] => 'border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'portuna_subtitle_border_type!' => 'none',
                            ],
                            'conditions' => [
                                'terms'    => [
                                    [
                                        'name'     => 'portuna_addon_items_decor_type',
                                        'operator' => '===',
                                        'value'    => 'shadow_square',
                                    ],
                                ],
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'portuna_sub_hover',
                    [
                        'label' => __( 'Hover', 'portuna-addon' ),
                    ]
                );

                    $this->add_control(
                        'portuna_subtitle_color_hover',
                        [
                            'label'     => __( 'Subtitle Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_hover' ] => 'color: {{VALUE}};'
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Text_Shadow::get_type(),
                        [
                            'label'    => __( 'Subtitle Text Shadow', 'portuna-addon' ),
                            'name'     => 'portuna_subtitle_shadow_hover',
                            'selector' => '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_hover' ],
                        ]
                    );

                    $this->add_control(
                        'portuna_subtitle_bgcolor_hover',
                        [
                            'label'     => __( 'Subtitle Background Color', 'portuna-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square_hover' ] => 'background-color: {{VALUE}};'
                            ],
                            'condition' => [
                                'portuna_addon_items_decor_type' => 'shadow_square',
                            ],
                        ]
                    );


                    $this->add_control(
                        'portuna_subtitle_border_color_hover',
                        [
                            'label'      => esc_html__( 'Border Color', 'simpli' ),
                            'type'       => Controls_Manager::COLOR,
                            'default'    => '',
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'wrap_content_subtitle_square_hover' ] => 'border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'portuna_subtitle_border_type!' => 'none',
                            ],
                            'conditions' => [
                                'terms'    => [
                                    [
                                        'name'     => 'portuna_addon_items_decor_type',
                                        'operator' => '===',
                                        'value'    => 'shadow_square',
                                    ],
                                ],
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render() {
        $this->server_side_render();
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new AdvancedHeading() );