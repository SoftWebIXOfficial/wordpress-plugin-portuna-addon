<?php

namespace PortunaAddon\Widgets\Edit;

defined( 'ABSPATH' ) || exit;

use \Elementor\Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Text_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Repeater;

use \PortunaAddon\Widgets\Portuna_Widget_Base;

class GoogleMap extends Portuna_Widget_Base {
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

//         wp_register_style(
//             'google-map-style-layout1',
//             plugin_dir_url( dirname( __FILE__ ) ) . 'google-map/assets/css/layout1.min.css',
//             [],
//             null
//         );

        wp_register_script(
            'google-map-style-layout1',
            plugin_dir_url( dirname( __FILE__ ) ) . 'google-map/assets/js/layout1.min.js',
            [],
            null
        );
    }

    public function get_style_depends() {
        return [ 'google-map-style-layout1' ];
    }

    public function get_script_depends() {
        return [ 'google-map-api', 'google-map-style-layout1' ]; //'google-map-style'
    }

    private static $css_map = [
        'map_container'                                 => ' .portuna-addon--google-map__container',
    ];

    /**
     * Return a dir path.
     */
    public function side_render_dir() {
        return dirname( __FILE__ );
    }

    public function get_name() {
        return 'portuna-addon-google-map';
    }

    public function get_title() {
        return __( 'Google Map', 'portuna-addon' );
    }

    public function get_icon() {
        return 'sm sm-google-map';
    }

    public function get_categories() {
        return [ 'portuna-addons-category' ];
    }

    protected function _register_controls() {
        // Initialize contents tab.
        $this->content_section1();
        $this->content_section2();
        $this->content_section3();
        $this->content_section4();
        $this->content_section5();
//
        $this->style_section1();
//         $this->style_section2();
    }

    public function content_section1() {
        $this->start_controls_section(
            'portuna_settings',
            [
                'label' => __( 'Map Settings', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'portuna_google_map_type',
                [
                    'label'     => __( 'Google Map Type', 'portuna-addon' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => [
                        'basic'   => __( 'Basic', 'portuna-addon' ),
                        'marker'  => __( 'Multiple Marker', 'portuna-addon' ),
                    ],
                    'default'   => 'basic',
                ]
            );

            $this->add_control(
                'portuna_address_type',
                [
                    'label'     => __( 'Address Type', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'toggle'    => false,
                    'options'   => [
                        'address'      => [
                            'title' => __( 'Address', 'portuna-addon' ),
                            'icon'  => 'fa fa-map',
                        ],
                        'coordinates'  => [
                            'title' => __( 'Coordinates', 'portuna-addon' ),
                            'icon'  => 'fa fa-map-marker',
                        ],
                    ],
                    'default'   => 'address',
                ],
            );

            $this->add_control(
                'portuna_map_address',
                [
                    'label'     => __( 'Address', 'portuna-addon' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => esc_html__( 'Dhaka, Bangladesh', 'portuna-addon' ),
                    'condition' => [
                        'portuna_address_type' => 'address'
                    ]
                ],
            );

            $this->add_control(
                'portuna_latitude',
                [
                    'label'     => __( 'Center Latitude', 'portuna-addon' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => esc_html__( '23.749981', 'portuna-addon' ),
                    'condition' => [
                        'portuna_address_type' => 'coordinates'
                    ]
                ],
            );

            $this->add_control(
                'portuna_longitude',
                [
                    'label'     => __( 'Center Longitude', 'portuna-addon' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => esc_html__( '90.365641', 'portuna-addon' ),
                    'condition' => [
                        'portuna_address_type' => 'coordinates'
                    ]
                ],
            );

        $this->end_controls_section();
    }

    public function content_section2() {
        $this->start_controls_section(
            'portuna_marker_settings',
            [
                'label' => __( 'Map Marker Settings', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'portuna_google_map_type' => [ 'basic' ],
                ]
            ]
        );

            $this->add_control(
                'portuna_marker_title',
                [
                    'label'       => __( 'Title', 'portuna-addon' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default'     => __( 'Google Map Title', 'portuna-addon' )

                ]
            );

            $this->add_control(
                'portuna_marker_content',
                [
                    'label'       => __( 'Content', 'portuna-addon' ),
                    'type'        => Controls_Manager::TEXTAREA,
                    'label_block' => true,
                    'default'     => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'portuna-addon' )

                ]
            );

            $this->add_control(
                'portuna_marker_content_opened_switch',
                [
                    'label'        => __( 'Marker content open by default?', 'portuna-addon' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => '',
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'portuna_marker_content_width',
                [
                    'label'              => __( 'Width (px)', 'portuna-addon' ),
                    'type'               => Controls_Manager::NUMBER,
                    'default'            => 250,
                    'min'                => 100,
                    'required'           => true,
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'portuna_marker_icon_switch',
                [
                    'label'        => __( 'Enable Custom Marker Icon?', 'portuna-addon' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => '',
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'portuna_marker_icon',
                [
                    'label'        => __( 'Marker Icon', 'portuna-addon' ),
                    'type'         => Controls_Manager::MEDIA,
                    'condition'    => [
                        'portuna_marker_icon_switch' => 'yes'
                    ]
                ]
            );

            $this->add_responsive_control(
                'portuna_marker_size_width',
                [
                    'label'      => __( 'Icon Width', 'portuna-addon' ),
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
                        'size'  => 30,
                    ],
                    'selectors' => [

                    ],
                    'condition' => [
                        'portuna_marker_icon_switch' => 'yes',
                    ]
                ]
            );

            $this->add_responsive_control(
                'portuna_marker_size_height',
                [
                    'label'      => __( 'Icon Height', 'portuna-addon' ),
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
                        'size'  => 30,
                    ],
                    'selectors' => [

                    ],
                    'condition' => [
                        'portuna_marker_icon_switch' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section();
    }

    public function content_section3() {
        $this->start_controls_section(
            'portuna_marker_settings_multiple',
            [
                'label' => __( 'Map Marker Settings', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'portuna_google_map_type' => [ 'marker' ], //'polyline', 'routes', 'static'
                ]
            ]
        );

            $repeater = new Repeater();

            $repeater->add_control(
                'portuna_marker_lat_multiple',
                [
                    'label'       => __( 'Marker Latitude', 'portuna-addon' ),
                    'type'        => Controls_Manager::TEXT,
                    'default'     => __( '23.754539', 'portuna-addon' ),
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                'portuna_marker_lng_multiple',
                [
                    'label'       => __( 'Marker Longitude', 'portuna-addon' ),
                    'type'        => Controls_Manager::TEXT,
                    'default'     => __( '90.3769106', 'portuna-addon' ),
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                'portuna_marker_title_multiple',
                [
                    'label'       => __( 'Title', 'portuna-addon' ),
                    'type'        => Controls_Manager::TEXT,
                    'default'     => __( 'Marker Title', 'portuna-addon' ),
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                'portuna_marker_content_multiple',
                [
                    'label'       => __( 'Content', 'portuna-addon' ),
                    'type'        => Controls_Manager::TEXTAREA,
                    'default'     => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'portuna-addon' ),
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                'portuna_marker_content_opened_switch',
                [
                    'label'        => __( 'Marker content open by default?', 'portuna-addon' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => '',
                    'return_value' => 'yes',
                ]
            );

            $repeater->add_control(
                'portuna_marker_content_width',
                [
                    'label'              => __( 'Width (px)', 'portuna-addon' ),
                    'type'               => Controls_Manager::NUMBER,
                    'default'            => 250,
                    'min'                => 100,
                    'required'           => true,
                    'frontend_available' => true,
                ]
            );

            $repeater->add_control(
                'portuna_marker_icon_switch_multiple',
                [
                    'label'        => __( 'Enable Custom Marker Icon?', 'portuna-addon' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => '',
                    'return_value' => 'yes',
                ]
            );

            $repeater->add_control(
                'portuna_marker_icon_multiple',
                [
                    'label'        => __( 'Marker Icon', 'portuna-addon' ),
                    'type'         => Controls_Manager::MEDIA,
                    'condition'    => [
                        'portuna_marker_icon_switch_multiple' => 'yes'
                    ]
                ]
            );

            $repeater->add_responsive_control(
                'portuna_marker_size_width_multiple',
                [
                    'label'      => __( 'Icon Width', 'portuna-addon' ),
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
                        'size'  => 30,
                    ],
                    'selectors' => [

                    ],
                    'condition' => [
                        'portuna_marker_icon_switch_multiple' => 'yes',
                    ]
                ]
            );

            $repeater->add_responsive_control(
                'portuna_marker_size_height_multiple',
                [
                    'label'      => __( 'Icon Height', 'portuna-addon' ),
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
                        'size'  => 30,
                    ],
                    'selectors' => [

                    ],
                    'condition' => [
                        'portuna_marker_icon_switch_multiple' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'portuna_marker_items_multiple',
                [
                    'label'       => __( 'Marker Items', 'portuna-addon' ),
                    'type'        => Controls_Manager::REPEATER,
                    'show_label'  => true,
                    'default'     => [
                        [
                            'portuna_marker_title_multiple' => __( 'Daffodil International University', 'portuna-addon' ),
                            'portuna_marker_lat_multiple'   => __( '23.754539', 'portuna-addon' ),
                            'portuna_marker_lon_multiple'   => __( '90.3769106', 'portuna-addon' ),
                        ],
                        [
                            'portuna_marker_title_multiple' => __( 'National Parliament House', 'portuna-addon' ),
                            'portuna_marker_lat_multiple'   => __( '23.7626233', 'portuna-addon' ),
                            'portuna_marker_lon_multiple'   => __( '90.3777502', 'portuna-addon' ),
                        ],
                    ],
                    'fields'      => $repeater->get_controls(),
                    'title_field' => '<i class="fa fa-map-marker" aria-hidden="true"></i> {{{ portuna_marker_title_multiple }}}',
                ]
            );

        $this->end_controls_section();
    }

    public function content_section4() {
        $this->start_controls_section(
            'portuna_map_controls',
            [
                'label' => __( 'Map Controls', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'portuna_map_zoom',
                [
                    'label'      => __( 'Zoom Level', 'portuna-addon' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range'  => [
                        'px' => [
                            'min' => 1,
                            'max' => 22,
                        ],
                    ],
                    'default'   => [
                        'unit'  => 'px',
                        'size'  => 2,
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'portuna_map_street_view',
                [
                    'label'              => __( 'Street View Control', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => 'yes',
                    'label_on'           => __( 'On', 'portuna-addon' ),
                    'label_off'          => __( 'Off', 'portuna-addon' ),
                    'return_value'       => 'yes',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'portuna_map_types',
                [
                    'label'              => __( 'Map Type Control', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => 'yes',
                    'label_on'           => __( 'On', 'portuna-addon' ),
                    'label_off'          => __( 'Off', 'portuna-addon' ),
                    'return_value'       => 'yes',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'portuna_map_zoom_control',
                [
                    'label'              => __( 'Zoom Control', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => 'yes',
                    'label_on'           => __( 'On', 'portuna-addon' ),
                    'label_off'          => __( 'Off', 'portuna-addon' ),
                    'return_value'       => 'yes',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'portuna_map_fullscreen_control',
                [
                    'label'              => __( 'Fullscreen Control', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => 'yes',
                    'label_on'           => __( 'On', 'portuna-addon' ),
                    'label_off'          => __( 'Off', 'portuna-addon' ),
                    'return_value'       => 'yes',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'portuna_map_scrollwheel_control',
                [
                    'label'              => __( 'Scroll Wheel Control', 'portuna-addon' ),
                    'type'               => Controls_Manager::SWITCHER,
                    'default'            => 'yes',
                    'label_on'           => __( 'On', 'portuna-addon' ),
                    'label_off'          => __( 'Off', 'portuna-addon' ),
                    'return_value'       => 'yes',
                    'frontend_available' => true,
                ]
            );

        $this->end_controls_section();
    }

    public function content_section5() {
        $this->start_controls_section(
            'portuna_map_style',
            [
                'label' => __( 'Map Style', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'portuna_map_source',
                [
                    'label'     => __( 'Map Source', 'portuna-addon' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'toggle'    => false,
                    'options'   => [
                        'gstandard'      => [
                            'title' => __( 'Google Standard', 'portuna-addon' ),
                            'icon'  => 'fa fa-map',
                        ],
                        'snazzymaps'  => [
                            'title' => __( 'Snazzy Maps', 'portuna-addon' ),
                            'icon'  => 'fa fa-map-marker',
                        ],
                        'custom'  => [
                            'title' => __( 'Custom', 'portuna-addon' ),
                            'icon'  => 'fa fa-edit',
                        ],
                    ],
                    'default'   => 'gstandard',
                ],
            );

            $this->add_control(
                'portuna_map_gstandard',
                [
                    'label'       => __( 'Map Style', 'portuna-addon' ),
                    'type'        => Controls_Manager::SELECT,
                    'options'     => [
                        'standard'  => __( 'Standard', 'portuna-addon' ),
                        'silver'    => __( 'Silver', 'portuna-addon' ),
                        'retro'     => __( 'Retro', 'portuna-addon' ),
                        'dark'      => __( 'Dark', 'portuna-addon' ),
                        'night'     => __( 'Night', 'portuna-addon' ),
                        'aubergine' => __( 'Aubergine', 'portuna-addon' ),
                    ],
                    'default'     => 'standard',
                    'description' => sprintf( '<a href="https://mapstyle.withgoogle.com/" target="_blank">%1$s</a> %2$s', __( 'Click here', 'portuna-addon' ), __( 'to generate your own theme and use JSON within Custom style field.', 'portuna-addon' ) ),
                    'condition'   => [
                        'portuna_map_source' => 'gstandard'
                    ],
                ]
            );

            $this->add_control(
                'portuna_map_snazzymaps',
                [
                    'label'         => __( 'SnazzyMaps Style', 'portuna-addon' ),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'default'    => __( 'Default', 'portuna-addon' ),
                        'simple'     => __( 'Simple', 'portuna-addon' ),
                        'colorful'   => __( 'Colorful', 'portuna-addon' ),
                        'complex'    => __( 'Complex', 'portuna-addon' ),
                        'dark'       => __( 'Dark', 'portuna-addon' ),
                        'greyscale'  => __( 'Greyscale', 'portuna-addon' ),
                        'light'      => __( 'Light', 'portuna-addon' ),
                        'monochrome' => __( 'Monochrome', 'portuna-addon' ),
                        'nolabels'   => __( 'No Labels', 'portuna-addon' ),
                        'twotone'    => __( 'Two Tone', 'portuna-addon' ),
                    ],
                    'default'       => 'default',
                    'description'   => sprintf( '<a href="https://snazzymaps.com/explore" target="_blank">%1$s</a> %2$s', __( 'Click here', 'portuna-addon' ), __( 'to explore more themes and use JSON within custom style field.', 'portuna-addon' ) ),
                    'condition'     => [
                        'portuna_map_source' => 'snazzymaps'
                    ],
                ]
            );

            $this->add_control(
                'portuna_map_custom',
                [
                    'label'       => __( 'Custom Style', 'portuna-addon' ),
                    'type'        => Controls_Manager::TEXTAREA,
                    'description' => sprintf( '<a href="https://mapstyle.withgoogle.com/" target="_blank">%1$s</a> %2$s',__( 'Click here', 'elementskit' ), __( 'to get JSON style code to style your map', 'elementskit' ) ),
                    'condition'   => [
                        'portuna_map_source' => 'custom'
                    ],
                ]
            );

        $this->end_controls_section();
    }

    public function style_section1() {
        $this->start_controls_section(
            'portuna_map_container',
            [
                'label' => __( 'Map Container', 'portuna-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'portuna_map_container_width',
                [
                    'label'      => __( 'Width', 'portuna-addon' ),
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

                    ],
                ]
            );

            $this->add_responsive_control(
                'portuna_map_container_height',
                [
                    'label'      => __( 'Height', 'portuna-addon' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'em', 'rem' ],
                    'range'  => [
                        'px' => [
                            'min' => 0,
                            'max' => 1200,
                        ],
                        '%' => [
                            'max' => 100,
                            'step' => 1,
                        ],
                        'em' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                        'rem' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'default'   => [
                        'unit'  => 'px',
                        'size'  => 400,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' . self::$css_map[ 'map_container' ]  => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();
    }

    protected function render() {
        $this->server_side_render();
    }

    public function set_map_style_theme( $settings ) {
        if ( $settings[ 'portuna_map_source' ] == 'custom' ) {
            return strip_tags( $settings[ 'portuna_map_custom' ] );
        } else {
            $themes = include( 'map-styles.php' );

            if ( isset( $themes[ $settings[ 'portuna_map_source' ] ][ $settings[ 'portuna_map_gstandard' ] ] ) ) {
                return $themes[ $settings[ 'portuna_map_source' ] ][ $settings[ 'portuna_map_gstandard' ] ];
            } elseif ( isset( $themes[ $settings[ 'portuna_map_source' ] ][ $settings[ 'portuna_map_snazzymaps' ] ] ) ) {
                return $themes[ $settings[ 'portuna_map_source' ] ][ $settings[ 'portuna_map_snazzymaps' ] ];
            } else {
                return;
            }
        }
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new GoogleMap() );