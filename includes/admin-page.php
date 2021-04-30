<?php

namespace PortunaAddon;

defined( 'ABSPATH' ) || exit;

class AdminPage {
    use Traits\Singleton;

    const PORTUNA_SLUG           = 'portuna-addon';
    const PORTUNA_NONCE_SETTINGS = 'portuna_addon_ajax_save_settings';
    const PORTUNA_NONCE_PRO      = 'portuna_addon_ajax_save_pro';
    public $options;

    public function __construct() {
        $this->options = \PortunaAddon\Helpers\Options::instance();
//         $this->options->save_option( 'validation', '' );
        add_action( 'admin_menu', [ $this, 'register_admin_panel_menu' ] );
        add_action( 'admin_menu', [ $this, 'update_admin_panel_menu' ], 20 );

        add_action( 'admin_enqueue_scripts', [ $this, 'admin_panel_enqueue_scripts' ], 100 );

        add_action( 'wp_ajax_' . self::PORTUNA_NONCE_SETTINGS, [ $this, 'save_user_data' ] );
        add_action( 'wp_ajax_' . self::PORTUNA_NONCE_PRO, [ $this, 'save_user_license' ] );

        add_action( 'in_admin_header', [ $this, 'remove_all_notices' ], PHP_INT_MAX );

        //var_dump( $this->options->get_option( 'user_data' ) );
    }

    public function save_user_data() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! check_ajax_referer( self::PORTUNA_NONCE_SETTINGS, 'nonce' ) ) {
            wp_send_json_error();
        }

        $posted_data = ! empty( $_POST[ 'data' ] ) ? $_POST[ 'data' ] : '';
        $data        = [];
        parse_str( $posted_data, $data );

        $this->options->save_option( 'user_data', $data );

        wp_send_json_success( $data );
    }

    public function save_user_license() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! check_ajax_referer( self::PORTUNA_NONCE_PRO, 'nonce' ) ) {
            wp_send_json_error();
        }

        $posted_data = ! empty( $_POST[ 'data' ] ) ? $_POST[ 'data' ] : '';
        $data        = [];
        parse_str( $posted_data, $data );

        $response = wp_remote_get(
            'http://localhost/InprogressProject/portuna/wp-json/api/v2/get?access_key=' . $data[ 'user_license' ][ 'license_key' ],
            [ 'timeout' => 15 ]
        );

        $body_data = json_decode( wp_remote_retrieve_body( $response ), false );

        $this->options->save_option( 'user_license', $data );

        if ( json_last_error() === JSON_ERROR_NONE ) {
            $this->options->save_option( 'validation', 'valid' );
            wp_send_json_success( $body_data );
            //$this->options->save_option( 'validation', 'valid' );
        } else {
            wp_send_json_error();
        }
    }

    public function data_client_save( $data ) {
        $userData = ! empty( $data[ 'user_data' ] ) ? $data[ 'user_data' ] : [];
        $this->options->save_option( 'user_data', $data );
    }

    public function admin_panel_enqueue_scripts() {
        $dir_js  = plugins_url( 'portuna-addon/assets/js/' );
        $dir_css = plugins_url( 'portuna-addon/assets/css/' );
        $version = '1.0.0';

        wp_enqueue_style(
            'portuna-admin-panel-style',
            $dir_css . 'admin-panel.min.css',
            [],
            $version
        );

        wp_enqueue_script(
            'portuna-admin-panel-script',
            $dir_js . 'admin-panel.min.js',
            [ 'jquery' ],
            $version,
            true
        );

        wp_localize_script(
            'portuna-admin-panel-script',
            'portunaAjax',
            [
                'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
                'nonces'            => [
                    'nonce_api'     =>  wp_create_nonce( self::PORTUNA_NONCE_SETTINGS ),
                    'pro_api'       =>  wp_create_nonce( self::PORTUNA_NONCE_PRO ),
                ],
                'actions'           => [
                    'action_api'    => self::PORTUNA_NONCE_SETTINGS,
                    'pro_api'       => self::PORTUNA_NONCE_PRO,
                ],
                'saveChangesText'   => __( 'Save Key', 'portuna-addon' ),
                'savedChangesText'  => __( 'Changes Saved', 'portuna-addon' ),
                'licenseKeySuccess' => __( 'Valid', 'portuna-addon' ),
                'licenseKeyError'   => __( 'Invalid', 'portuna-addon' ),
            ]
        );
    }

    public function register_admin_panel_menu() {
        add_menu_page(
            __( 'Portuna Addon', 'portuna-addon' ),
            __( 'Portuna', 'portuna-addon' ),
            'manage_options',
            self::PORTUNA_SLUG,
            [ $this, 'admin_frontend_init' ],
            'dashicons-admin-tools',
            3.22
        );

        $submenu_item = $this->admin_submenu_navigation();

        if ( is_array( $submenu_item ) ) :
            foreach ( $submenu_item as $key => $data ) :
                if ( empty( $data[ 'frontend_file' ] ) || ! is_callable( $data[ 'frontend_file' ] ) ) :
                    continue;
                endif;

                add_submenu_page(
                    self::PORTUNA_SLUG,
                    sprintf(
                        __( '%s Portuna', 'portuna-addon'),
                        $data[ 'sub_title' ]
                    ),
                    $data[ 'sub_title' ],
                    'manage_options',
                    self::PORTUNA_SLUG . '#' . $key,
                    $data[ 'frontend_file' ]
                );
            endforeach;
        endif;
    }

    public function admin_submenu_navigation() {
        $submenu_item = [
            'settings'  => [
                'sub_title'     => __( 'Settings', 'portuna-addon' ),
                'frontend_file' => [ $this, 'admin_frontend_init' ],
            ],
            'pro'       => [
                'sub_title'     => __( 'Pro', 'portuna-addon' ),
                'frontend_file' => [ $this, 'admin_frontend_pro' ],
            ],
        ];

        return apply_filters( 'get_admin_submenus', $submenu_item );
    }

    /**
     * Update admin menus.
     */
    public function update_admin_panel_menu() {
        if ( ! current_user_can( 'manage_options' ) ) :
           return;
        endif;

        global $submenu;

        $menu = $submenu[ self::PORTUNA_SLUG ];
        array_shift( $menu );
        $submenu[ self::PORTUNA_SLUG ] = $menu;
    }

    /**
     * Frontend options.
     */
    public function load_file_frontend( $file_name ) {
        $get_file = dirname( __FILE__ ) . '/views/admin-panel-' . $file_name . '.php';

        if ( is_readable( $get_file ) ) :
            include( $get_file );
        endif;
    }

    public function admin_frontend_init() {
        $this->load_file_frontend( 'init' );
    }

    public function admin_frontend_settings() {
        $this->load_file_frontend( 'settings' );
    }

    public function admin_frontend_pro() {
        $this->load_file_frontend( 'pro' );
    }

    /**
     * Remove all notices messages.
     */
    public function portuna_is_page() {
        return (
            isset( $_GET[ 'page' ] )
            && $_GET[ 'page' ] === self::PORTUNA_SLUG
        );
    }

    public function remove_all_notices() {
        if ( $this->portuna_is_page() ) :
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
        endif;
    }
}