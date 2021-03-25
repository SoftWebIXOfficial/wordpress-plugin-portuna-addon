<?php

namespace PortunaAddon\Helpers;

defined( 'ABSPATH' ) || exit;

class Options {
    use \PortunaAddon\Traits\Singleton;

    private static $key = 'portuna_addon_options';

    public function get_option( $key, $default = '' ) {
        $data_all = get_option( self::$key );
        return ( isset( $data_all[ $key ] ) && $data_all[ $key ] != '' ) ? $data_all[ $key ] : $default;
    }

    public function get_settings( $key, $default = '' ) {
        $data_all = $this->get_option( 'settings', [] );
        return ( isset( $data_all[ $key ] ) && $data_all[ $key ] != '' ) ? $data_all[ $key ] : $default;
    }

    public function save_option( $key, $value = '' ) {
        $data_all         = get_option( self::$key );
        $data_all[ $key ] = $value;
        update_option( 'portuna_addon_options', $data_all );
    }
}