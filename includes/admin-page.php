<?php

namespace PortunaAddon;

defined( 'ABSPATH' ) || exit;

class AdminPage {
    use Traits\Singleton;

    const PORTUNA_SLUG  = 'portuna-addon';
    const PORTUNA_NONCE = 'portuna_addon_ajax';
    public $options;

    public function __construct() {
        $this->options = \PortunaAddon\Helpers\Options::instance();

        add_action( 'admin_menu', [ $this, 'register_admin_panel_menu' ] );
        add_action( 'admin_menu', [ $this, 'update_admin_panel_menu' ], 20 );

        add_action( 'admin_enqueue_scripts', [ $this, 'admin_panel_enqueue_scripts' ], 100 );

        add_action( 'wp_ajax_' . self::PORTUNA_NONCE, [ $this, 'save_user_data' ] );

        add_action( 'in_admin_header', [ $this, 'remove_all_notices' ], PHP_INT_MAX );
    }

    public function save_user_data() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! check_ajax_referer( self::PORTUNA_NONCE, 'nonce' ) ) {
            wp_send_json_error();
        }

        $posted_data = ! empty( $_POST[ 'data' ] ) ? $_POST[ 'data' ] : '';
        $data        = [];
        parse_str( $posted_data, $data );

        $this->options->save_option( 'user_data', $data );

        wp_send_json_success();
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

        wp_enqueue_script(
            'portuna-admin-panel-script',
            $dir_js . 'admin-panel.min.js',
            [ 'jquery' ],
            $version,
            true
        );

        wp_enqueue_script(
            'pay-fondy',
            ( is_ssl() ? 'https' : 'http' ) . '://pay.fondy.eu/static_common/v1/checkout/ipsp.js',
            null,
            null,
            true
        );

        wp_localize_script(
            'portuna-admin-panel-script',
            'portunaAjax',
            [
                'nonce'            => wp_create_nonce( self::PORTUNA_NONCE ),
                'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
                'action'           => self::PORTUNA_NONCE,
                'saveChangesText'  => __( 'Save Changes', 'simpli' ),
                'savedChangesText' => __( 'Changes Saved', 'simpli' ),
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