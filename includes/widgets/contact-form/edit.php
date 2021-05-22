<?php

namespace PortunaAddon\Widgets\Edit;

defined( 'ABSPATH' ) || exit;

use \Elementor\Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Repeater;
use \Elementor\Group_Control_Typography;

use \PortunaAddon\Widgets\Portuna_Widget_Base;
use \PortunaAddon\Helpers\ControlsManager;

class ContactForm extends Portuna_Widget_Base {
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'contact-form-style-layout1',
            plugin_dir_url( dirname( __FILE__ ) ) . 'contact-form/assets/css/layout1.min.css',
            [],
            null
        );
    }

    public function get_style_depends() {
        return [ 'contact-form-style-layout1' ];
    }

    /**
     * Map the widget controls.
     * Used to allow the user to customize items.
     */
    private static $css_map = [
        ''                     => ' '
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
        return 'portuna_addon_contact_form';
    }

    /**
     * Widget Title – The get_title() method, which again, is a very simple one,
     * you need to return the widget title that will be displayed as the widget label.
     */
    public function get_title() {
        return __( 'Contact Form', 'portuna-addon' );
    }

    /**
     * Widget Icon – The get_icon() method, is an optional but recommended method,
     * it lets you set the widget icon. you can use any of the eicon or
     * font-awesome icons, simply return the class name as a string.
     */
    public function get_icon() {
        return 'mdi mdi-email-variant';
    }

    public function get_keywords() {
        return [ 'mail', 'contact', 'form' ];
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
        $this->form_fields_settings();
        //$this->settings_content2();
    }

    public function form_fields_settings() {
        /**
         * Start content section.
         */
        $this->start_controls_section(
            'portuna_form_fields',
            [
                'label' => __( 'Form Fields', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $repeater = new Repeater();

            $repeater->start_controls_tabs( 'fields_settings' );

                $repeater->start_controls_tab(
                    'content',
                    [
                        'label'     => __( 'Content', 'portuna-addon' ),
                    ]
                );

                    $repeater->add_control(
                        'field_type',
                        [
                            'label'   => __( 'Field Type', 'portuna-addon' ),
                            'type'    => Controls_Manager::SELECT,
                            'options' => [
                                'text'       => __( 'Text', 'portuna-addon' ),
                                'email'      => __( 'Email', 'portuna-addon' ),
                                'textarea'   => __( 'Textarea', 'portuna-addon' ),
                                'url'        => __( 'URL', 'portuna-addon' ),
                                'tel'        => __( 'Tel', 'portuna-addon' ),
                                'radio'      => __( 'Radio', 'portuna-addon' ),
                                'select'     => __( 'Select', 'portuna-addon' ),
                                'checkbox'   => __( 'Checkbox', 'portuna-addon' ),
                                'acceptance' => __( 'Acceptance', 'portuna-addon' ),
                                'number'     => __( 'Number', 'portuna-addon' ),
                                'date'       => __( 'Date', 'portuna-addon' ),
                                'time'       => __( 'Time', 'portuna-addon' ),
                                'upload'     => __( 'File Upload', 'portuna-addon' ),
                                'password'   => __( 'Password', 'portuna-addon' ),
                                'html'       => __( 'HTML', 'portuna-addon' ),
                                'hidden'     => __( 'Hidden', 'portuna-addon' ),
                            ],
                            'default' => 'text',
                        ]
                    );

                $repeater->end_controls_tab();

                $repeater->start_controls_tab(
                    'advanced',
                    [
                        'label'     => __( 'Advanced', 'portuna-addon' ),
                    ]
                );

                $repeater->end_controls_tab();

            $repeater->end_controls_tabs();

            $this->add_control(
                'fields_item',
                [
                    'label'       => __( 'Fields Items', 'portuna-addon' ),
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
                        'value'	       => 'fas fa-long-arrow-alt-left',
                        'library'      => 'fa-solid',
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
                        'value'	       => 'fas fa-long-arrow-alt-right',
                        'library'      => 'fa-solid',
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
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrows' ]             => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_custom_arrows_icon' ] => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_custom_arrows_svg' ]  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                    'label'     => __( 'Arrows Between Space', 'portuna-addon' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
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
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrow_left' ]  => 'margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrow_right' ] => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'slide_nav'                 => [ 'both', 'arrows' ],
                        'slide_arrows_type'         => 'custom-pro',
                        'slide_arrows_distance_pro' => 'together',
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
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrow_left' ]  => 'left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrow_right' ] => 'right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'slide_nav'         => [ 'both', 'arrows' ],
                        'slide_arrows_type' => [ 'arrows', 'custom-pro' ],
                        'slide_arrows_distance_pro!' => 'together',
                    ]
                ]
            );

            $this->add_control(
                'slide_arrows_separator_type_pro',
                [
                    'label'     => __( 'Separator Type', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'default'   => 'text',
                    'toggle'    => false,
                    'options'   => [
                        'text'      => [
                            'title' => __( 'Text', 'portuna-addon' ),
                            'icon'  => 'eicon-text',
                        ],
                        'icon'     => [
                            'title' => __( 'Icon', 'portuna-addon' ),
                            'icon'  => 'eicon-star-o',
                        ]
                    ],
                    'condition' => [
                        'slide_nav'                 => [ 'both', 'arrows' ],
                        'slide_arrows_type'         => 'custom-pro',
                        'slide_arrows_distance_pro' => 'together',
                    ],
                ]
            );

            $this->add_control(
                'slide_arrows_separator_text_pro',
                [
                    'label'           => __( 'Separator Text', 'portuna-addon' ),
                    'type'            => Controls_Manager::TEXT,
                    'default'         => __( '|', 'portuna-addon' ),
                    'label_block'     => true,
                    'condition'       => [
                        'slide_nav'                       => [ 'both', 'arrows' ],
                        'slide_arrows_type'               => 'custom-pro',
                        'slide_arrows_distance_pro'       => 'together',
                        'slide_arrows_separator_type_pro' => 'text',
                    ],
                ]
            );

            $this->add_control(
                'slide_arrows_separator_color_pro',
                [
                    'label'           => __( 'Separator Color', 'portuna-addon' ),
                    'type'            => Controls_Manager::COLOR,
                    'default'         => '',
                    'selectors'       => [
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_separator' ] => 'color: {{VALUE}};',
                    ],
                    'condition'       => [
                        'slide_nav'                       => [ 'both', 'arrows' ],
                        'slide_arrows_type'               => 'custom-pro',
                        'slide_arrows_distance_pro'       => 'together',
                        'slide_arrows_separator_type_pro' => 'text',
                    ],
                ]
            );

            $this->add_control(
                'slide_arrows_separator_icons_pro',
                [
                    'label'            => __( 'Separator Icon', 'portuna-addon' ),
                    'type'             => Controls_Manager::ICONS,
                    'fa4compatibility' => 'slide_arrows_separator_icon_pro',
                    'default'	  	   => [
                        'value'	       => 'fas fa-slash',
                        'library'      => 'fa-solid',
                    ],
                    'condition'        => [
                        'slide_nav'                       => [ 'both', 'arrows' ],
                        'slide_arrows_type'               => 'custom-pro',
                        'slide_arrows_distance_pro'       => 'together',
                        'slide_arrows_separator_type_pro' => 'icon',
                    ],
                ]
            );

            $this->add_control(
                'slide_arrows_separator_icon_color_pro',
                [
                    'label'           => __( 'Separator Icon Color', 'portuna-addon' ),
                    'type'            => Controls_Manager::COLOR,
                    'default'         => '',
                    'selectors'       => [
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_separator_icon' ] => 'color: {{VALUE}};',
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_separator_svg' ]  => 'fill: {{VALUE}};',
                    ],
                    'condition'       => [
                        'slide_nav'                       => [ 'both', 'arrows' ],
                        'slide_arrows_type'               => 'custom-pro',
                        'slide_arrows_distance_pro'       => 'together',
                        'slide_arrows_separator_type_pro' => 'icon',
                    ],
                ]
            );

            $this->add_control(
                'slide_arrows_separator_icon_size_pro',
                [
                    'label'      => __( 'Separator Icon Size', 'portuna-addon' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'em', 'rem' ],
                    'range'      => [
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
                        'size'  => 16,
                    ],
                    'selectors'       => [
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_separator_icon' ] => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_separator_svg' ]  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'       => [
                        'slide_nav'                       => [ 'both', 'arrows' ],
                        'slide_arrows_type'               => 'custom-pro',
                        'slide_arrows_distance_pro'       => 'together',
                        'slide_arrows_separator_type_pro' => 'icon',
                    ],
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
                        'slide_nav'                 => [ 'both', 'arrows' ],
                        'slide_arrows_type'         => 'custom-pro',
                        'slide_arrows_distance_pro' => 'together',
                    ],
                    'separator' => 'before'
                ]
            );

            $this->add_responsive_control(
                'slide_arrows_position_hr_offset_left',
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
                        'body:not(.rtl) {{WRAPPER}}' . self::$css_map[ 'swiper_arrows_together' ]  => 'left: {{SIZE}}{{UNIT}}',
                        'body.rtl {{WRAPPER}}' . self::$css_map[ 'swiper_arrows_together' ]        => 'right: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'slide_nav'                            => [ 'both', 'arrows' ],
                        'slide_arrows_type'                    => 'custom-pro',
                        'slide_arrows_distance_pro'            => 'together',
                        'slide_arrows_position_hr_orientation' => 'left',
                    ]
                ]
            );

            $this->add_responsive_control(
                'slide_arrows_position_hr_offset_right',
                [
                    'label'  => __( 'Offset (Horizontal)', 'portuna-addon' ),
                    'type'   => Controls_Manager::SLIDER,
                    'range'  => [
                        'px' => [
                            'min'  => -1000,
                            'max'  => 1000,
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
                        'body:not(.rtl) {{WRAPPER}}' . self::$css_map[ 'swiper_arrows_together' ]  => 'right: {{SIZE}}{{UNIT}}',
                        'body.rtl {{WRAPPER}}' . self::$css_map[ 'swiper_arrows_together' ]        => 'left: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'slide_nav'                            => [ 'both', 'arrows' ],
                        'slide_arrows_type'                    => 'custom-pro',
                        'slide_arrows_distance_pro'            => 'together',
                        'slide_arrows_position_hr_orientation' => 'right',
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
                            'icon'  => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'portuna-addon' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ]
                    ],
                    'condition' => [
                        'slide_nav'                 => [ 'both', 'arrows' ],
                        'slide_arrows_type'         => 'custom-pro',
                        'slide_arrows_distance_pro' => 'together',
                    ]
                ]
            );

            $this->add_responsive_control(
                'slide_arrows_position_vr_offset_top',
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
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrows_together' ] => 'top: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'slide_nav'                            => [ 'both', 'arrows' ],
                        'slide_arrows_type'                    => 'custom-pro',
                        'slide_arrows_distance_pro'            => 'together',
                        'slide_arrows_position_vr_orientation' => 'top',
                    ]
                ]
            );

            $this->add_responsive_control(
                'slide_arrows_position_vr_offset_bottom',
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
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_arrows_together' ] => 'bottom: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'slide_nav'                            => [ 'both', 'arrows' ],
                        'slide_arrows_type'                    => 'custom-pro',
                        'slide_arrows_distance_pro'            => 'together',
                        'slide_arrows_position_vr_orientation' => 'bottom',
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
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_arrows' ]             => 'color: {{VALUE}};',
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_custom_arrows_icon' ] => 'color: {{VALUE}};',
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_custom_arrows_svg' ]  => 'fill: {{VALUE}};',
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
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_arrows_hover' ]   => 'color: {{VALUE}};',
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_arrows_i_hover' ] => 'color: {{VALUE}};',
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
                    ],
                    'frontend_available' => true,
                    'condition'          => [
                        'slide_nav'      => [ 'both', 'dots' ],
                    ]
                ]
            );

            $this->add_control(
                'slide_pagination_border_type',
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
                    'selectors' => [
                        '{{SELECTOR}}' . self::$css_map[ 'swiper_pagination_bullet' ] => 'border-style: {{VALUE}};',
                    ],
                    'condition' => [
                        'slide_nav'             => [ 'both', 'dots' ],
                        'slide_pagination_type' => 'bullets',
                    ],
                ]
            );

            $this->add_responsive_control(
                'slide_pagination_size',
                [
                    'label'      => __( 'Pagination Bullets Size', 'portuna-addon' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range'  => [
                        'px' => [
                            'min' => 0,
                            'max' => 1100,
                        ],
                    ],
                    'default'   => [
                        'unit'  => 'px',
                        'size'  => 15,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_bullet' ]  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'slide_nav'                     => [ 'both', 'dots' ],
                        'slide_pagination_type'         => [ 'bullets' ],
                    ]
                ]
            );

            $this->add_responsive_control(
                'slide_pagination_border_width',
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
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_bullet' ] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'slide_nav'                     => [ 'both', 'dots' ],
                        'slide_pagination_border_type!' => 'none',
                        'slide_pagination_type'         => [ 'bullets' ],
                    ],
                ]
            );

            $this->add_responsive_control(
                'slide_pagination_border_radius',
                [
                    'label'      => esc_html__( 'Border Radius', 'portuna-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_bullet' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'slide_nav'                     => [ 'both', 'dots' ],
                        'slide_pagination_type'         => [ 'bullets' ],
                    ],
                ]
            );

            $this->add_responsive_control(
                'slide_pagination_hr_orientation',
                [
                    'label'   => __( 'Horizontal Orientation', 'portuna-addon' ),
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
                        'slide_nav'                 => [ 'both', 'dots' ],
                        'slide_pagination_type'     => [ 'bullets', 'fraction' ],
                    ],
                    'separator' => 'before'
                ]
            );

            $this->add_responsive_control(
                'slide_pagination_hr_offset_left',
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
                        'body:not(.rtl) {{WRAPPER}}' . self::$css_map[ 'swiper_pagination_position' ]      => 'left: {{SIZE}}{{UNIT}}',
                        'body:not(.rtl) {{WRAPPER}}' . self::$css_map[ 'swiper_pagination_fraction_pos' ]  => 'left: {{SIZE}}{{UNIT}}',
                        'body.rtl {{WRAPPER}}' . self::$css_map[ 'swiper_pagination_position' ]            => 'right: {{SIZE}}{{UNIT}}',
                        'body.rtl {{WRAPPER}}' . self::$css_map[ 'swiper_pagination_fraction_pos' ]        => 'right: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'slide_nav'                       => [ 'both', 'dots' ],
                        'slide_pagination_type'           => [ 'bullets', 'fraction' ],
                        'slide_pagination_hr_orientation' => 'left',
                    ]
                ]
            );

            $this->add_responsive_control(
                'slide_pagination_hr_offset_right',
                [
                    'label'  => __( 'Offset (Horizontal)', 'portuna-addon' ),
                    'type'   => Controls_Manager::SLIDER,
                    'range'  => [
                        'px' => [
                            'min'  => -1000,
                            'max'  => 1000,
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
                        'body:not(.rtl) {{WRAPPER}}' . self::$css_map[ 'swiper_pagination_position' ]      => 'right: {{SIZE}}{{UNIT}}',
                        'body:not(.rtl) {{WRAPPER}}' . self::$css_map[ 'swiper_pagination_fraction_pos' ]  => 'right: {{SIZE}}{{UNIT}}',
                        'body.rtl {{WRAPPER}}' . self::$css_map[ 'swiper_pagination_position' ]            => 'left: {{SIZE}}{{UNIT}}',
                        'body.rtl {{WRAPPER}}' . self::$css_map[ 'swiper_pagination_fraction_pos' ]        => 'left: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'slide_nav'                       => [ 'both', 'dots' ],
                        'slide_pagination_type'           => [ 'bullets', 'fraction' ],
                        'slide_pagination_hr_orientation' => 'right',
                    ]
                ]
            );

            $this->add_responsive_control(
                'slide_pagination_vr_orientation',
                [
                    'label'   => __( 'Vertical Orientation', 'portuna-addon' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'toggle'  => false,
                    'default' => 'bottom',
                    'options' => [
                        'top'    => [
                            'title' => __( 'Top', 'portuna-addon' ),
                            'icon'  => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'portuna-addon' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ]
                    ],
                    'condition' => [
                        'slide_nav'                 => [ 'both', 'dots' ],
                        'slide_pagination_type'     => [ 'bullets', 'fraction' ],
                    ]
                ]
            );

            $this->add_responsive_control(
                'slide_pagination_vr_offset_top',
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
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_position' ]     => 'top: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_fraction_pos' ] => 'top: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'slide_nav'                       => [ 'both', 'dots' ],
                        'slide_pagination_type'           => [ 'bullets', 'fraction' ],
                        'slide_pagination_vr_orientation' => 'top',
                    ]
                ]
            );

            $this->add_responsive_control(
                'slide_pagination_vr_offset_bottom',
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
                        'size'  => 40,
                        'unit'  => 'px'
                    ],
                    'size_units' => [ 'px', '%', 'vw', 'vh' ],
                    'selectors'  => [
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_position' ]     => 'bottom: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_fraction_pos' ] => 'bottom: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'slide_nav'                       => [ 'both', 'dots' ],
                        'slide_pagination_type'           => [ 'bullets', 'fraction' ],
                        'slide_pagination_vr_orientation' => 'bottom',
                    ],
                    'separator'       => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label'           => __( 'Pagination Typography', 'portuna-addon' ),
                    'name'            => 'pagination_typography',
                    'selector'        => '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_fraction' ],
                    'fields_options'  => [
                        'font_weight' => [
                            'default' => '400'
                        ]
                    ],
                    'condition'       => [
                        'slide_nav'             => [ 'both', 'dots' ],
                        'slide_pagination_type' => 'fraction',
                    ],
                ]
            );

            $this->start_controls_tabs( 'pagination_effect' );

                $this->start_controls_tab(
                    'slide_pagination_normal',
                    [
                        'label'     => __( 'Normal', 'portuna-addon' ),
                        'condition' => [
                            'slide_nav'  => [ 'both', 'dots' ],
                        ]
                    ]
                );

                    $this->add_control(
                        'slide_pagination_bg_color',
                        [
                            'label'      => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_bullet' ]      => 'background: {{VALUE}};',
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_progressbar_bgcolor' ] => 'background: {{VALUE}};',
                            ],
                            'condition'  => [
                                'slide_nav'                     => [ 'both', 'dots' ],
                                'slide_pagination_type'         => [ 'bullets', 'progressbar' ],
                            ]
                        ]
                    );

                    $this->add_control(
                        'slide_pagination_progress_bg_color',
                        [
                            'label'      => esc_html__( 'Progress Background Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_progressbar_bgcolor_fill' ] => 'background: {{VALUE}};',
                            ],
                            'condition'  => [
                                'slide_nav'                     => [ 'both', 'dots' ],
                                'slide_pagination_type'         => [ 'progressbar' ],
                            ]
                        ]
                    );

                    $this->add_control(
                        'slide_pagination_border_color',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_bullet' ] => 'border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'slide_pagination_border_type!' => 'none',
                                'slide_pagination_type'         => [ 'bullets' ],
                            ],
                        ]
                    );

                    $this->add_control(
                        'slide_pagination_fraction_color',
                        [
                            'label'      => esc_html__( 'Text Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_fraction_color' ] => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'slide_pagination_type'         => [ 'fraction' ],
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'slide_pagination_hover',
                    [
                        'label'     => __( 'Hover', 'portuna-addon' ),
                        'condition' => [
                            'slide_nav'             => [ 'both', 'dots' ],
                            'slide_pagination_type' => [ 'bullets', 'fraction' ],
                        ]
                    ]
                );

                    $this->add_control(
                        'slide_pagination_bg_color_hover',
                        [
                            'label'      => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_bullet_hover' ] => 'background: {{VALUE}};',
                            ],
                            'condition'  => [
                                'slide_nav'                     => [ 'both', 'dots' ],
                                'slide_pagination_type'         => [ 'bullets' ],
                            ]
                        ]
                    );

                    $this->add_control(
                        'slide_pagination_border_color_hover',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_bullet_hover' ] => 'border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'slide_pagination_border_type!' => 'none',
                                'slide_pagination_type'         => [ 'bullets' ],
                            ],
                        ]
                    );

                    $this->add_control(
                        'slide_pagination_fraction_color_hover',
                        [
                            'label'      => esc_html__( 'Text Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_fraction_color_hover' ] => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'slide_pagination_type'         => [ 'fraction' ],
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'slide_pagination_active',
                    [
                        'label'     => __( 'Active', 'portuna-addon' ),
                        'condition' => [
                            'slide_nav'             => [ 'both', 'dots' ],
                            'slide_pagination_type' => [ 'bullets' ],
                        ]
                    ]
                );

                    $this->add_control(
                        'slide_pagination_bg_color_active',
                        [
                            'label'      => esc_html__( 'Background Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_bullet_active' ] => 'background: {{VALUE}};',
                            ],
                            'condition'  => [
                                'slide_nav'                     => [ 'both', 'dots' ],
                                'slide_pagination_type'         => [ 'bullets' ],
                            ]
                        ]
                    );

                    $this->add_control(
                        'slide_pagination_border_color_active',
                        [
                            'label'      => esc_html__( 'Border Color', 'portuna-addon' ),
                            'type'       => Controls_Manager::COLOR,
                            'selectors'  => [
                                '{{WRAPPER}}' . self::$css_map[ 'swiper_pagination_bullet_active' ] => 'border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'slide_pagination_border_type!' => 'none',
                                'slide_pagination_type'         => [ 'bullets' ],
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'slide_center',
                [
                    'label'              => __( 'Slide Center', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => '',
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
                    'default'            => '',
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
                    'default'            => '',
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
                    'default'            => '',
                    'return_value'       => 'yes',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'slide_simulate_touch',
                [
                    'label'              => __( 'Simulate Touch', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => '',
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

Plugin::instance()->widgets_manager->register_widget_type( new ContactForm() );