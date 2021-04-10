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

