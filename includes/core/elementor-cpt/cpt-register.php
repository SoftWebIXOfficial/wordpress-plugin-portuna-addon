<?php

namespace PortunaAddon\Core;

defined( 'ABSPATH' ) || exit;

class Register_Cpt {

    protected $name_post = 'portuna_content';

    public function register_cpt() {
        $labels   = [
            'name'                  => _x( 'Portuna Addon items', 'Post Type General Name', 'portuna-addon' ),
            'singular_name'         => _x( 'Portuna Addon item', 'Post Type Singular Name', 'portuna-addon' ),
            'menu_name'             => esc_html__( 'Portuna Addon item', 'portuna-addon' ),
        ];

        $rewrite  = [
            'slug'                  => 'portuna-content',
            'with_front'            => true,
            'pages'                 => false,
            'feeds'                 => false
        ];

        $supports = apply_filters( 'portuna-addon/cpt/register/supports', [ 'title', 'editor', 'elementor', 'permalink' ] );

        $args     = [
            'labels'                => $labels,
            'taxonomies'            => [],
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'supports'              => $supports,
            'can_export'            => true,
            'has_archive'           => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'query_var'             => true,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'menu_position'         => 5,
            'rest_base'             => 'portuna-content',
        ];

        register_post_type( $this->name_post, $args );
    }

    public function flush_rewrites() {
        $this->register_cpt();
        flush_rewrite_rules();
    }

    public function __construct() {
        $this->register_cpt();

        register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
        register_activation_hook( __FILE__, [ $this, 'flush_rewrites' ] );
    }
}

new Register_Cpt();