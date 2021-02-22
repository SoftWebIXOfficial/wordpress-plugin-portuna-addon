<?php

/**
 * Portuna Addon Plugin
 *
 * @package Portuna
 *
 * @wordpress-plugin
 *
 * Plugin Name: Portuna Addon Plugin
 * Description: Portuna Addon Plugin for Elementor
 * Version:     1.0.0
 * Author:      SoftWebIX
 * Author URI:  https://softwebixpreview.com/portuna/
 * License:     Apache-2.0
 * License URI: http://www.apache.org/licenses/LICENSE-2.0
 * Text domain: portuna
 *
 */

defined( 'ABSPATH' ) || exit;

define( 'PORTUNA_FILE', __FILE__ );
define( 'PORTUNA_PLUGIN_VERSION', '1.0.0' );
define( 'PORTUNA_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'PORTUNA_PLUGIN_DIR', plugin_dir_path( PORTUNA_FILE ) );
define( 'PORTUNA_PLUGIN_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'PORTUNA_PLUGIN_ASSETS', trailingslashit( PORTUNA_PLUGIN_PATH . 'assets' ) );

require_once PORTUNA_PLUGIN_PATH . 'vendor/autoload.php';

function init_plugin() {
    return \PortunaAddon\Base::instance();
}

add_action( 'plugins_loaded', 'init_plugin' );

//add_action( 'init', 'register_post_types' );

function register_post_types() {
    $labels = [
        'name'                  => _x( 'Portuna Addon items', 'Post Type General Name', 'portuna-addon' ),
        'singular_name'         => _x( 'Portuna Addon item', 'Post Type Singular Name', 'portuna-addon' ),
        'menu_name'             => esc_html__( 'Portuna Addon item', 'portuna-addon' ),
    ];

    $rewrite = [
        'slug'                  => 'portuna-content',
        'with_front'            => true,
        'pages'                 => false,
        'feeds'                 => false
    ];

    $supports = apply_filters( 'portuna-addon/cpt/register/supports', [ 'title', 'editor', 'elementor', 'permalink' ] );

    $args = [
        'labels'                => $labels,
        'supports'              => $supports,
        'taxonomies'            => [],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
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

    register_post_type( 'portuna_content', $args );
}