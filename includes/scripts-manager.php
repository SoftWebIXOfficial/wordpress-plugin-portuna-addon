<?php

namespace PortunaAddon;

defined( 'ABSPATH' ) || exit;

class ScriptsManager {
    use \PortunaAddon\Traits\Singleton;

    public function common_js() {
            ob_start();
        ?>
            var portuna = {
                resturl: '<?php echo get_rest_url() . 'portuna/v1/'; ?>',
            }
        <?php
            $output = ob_get_contents();
            ob_end_clean();

            return $output;
    }

    public function front_js() {
        $js = $this->common_js();

        wp_enqueue_script(
            'portuna-addon-frontend-scripts',
            plugins_url( 'portuna-addon/assets/' ) . 'js/front.js',
            [],
            null
        );

        wp_add_inline_script( 'portuna-addon-frontend-scripts', $js );
    }

    public function admin_js() {
        echo '<script type="text/javascript"\n';
            echo Utils_Pro::instance()->render( $this->common_js() );
        echo '\n</script>';
    }

    public function register_styles() {
        // Vendors
        wp_enqueue_style(
            'portuna-vendors-swiper',
            plugins_url( 'portuna-addon/assets/' ) . 'vendors/swiper/css/swiper.min.css',
            [],
            '6.4.15'
        );

        wp_enqueue_style(
            'portuna-vendors-main',
            plugins_url( 'portuna-addon/assets/' ) . 'vendors/index.min.css',
            [],
            null
        );
    }

    public function register_scripts() {
        $options        = \PortunaAddon\Helpers\Options::instance();
        $userData       = $options->get_option( 'user_data' );
        $google_api_key = isset( $userData[ 'user_data' ][ 'google_api_key' ] ) && ! empty( $userData[ 'user_data' ][ 'google_api_key' ] ) ? $userData[ 'user_data' ][ 'google_api_key' ] : '';

        // Vendors
        wp_enqueue_script(
            'portuna-vendors-swiper',
            plugins_url( 'portuna-addon/assets/' ) . 'vendors/swiper/js/swiper.min.js',
            [],
            '6.4.15',
            true
        );

        wp_enqueue_script(
            'portuna-vendors-main',
            plugins_url( 'portuna-addon/assets/' ) . 'vendors/index.min.js',
            [ 'jquery', 'imagesloaded' ],
            null,
            true
        );

        wp_register_script(
            'google-map-style',
            plugins_url( 'portuna-addon/assets/' ) . 'vendors/google-maps.min.js',
            [],
            null,
            true
        );

        wp_register_script(
            'google-map-api',
            ( is_ssl() ? 'https' : 'http' ) . '://maps.googleapis.com/maps/api/js?key=' . $google_api_key,
            null,
            null,
            true
        );
    }

    public function editor_enqueue_scripts() {
        wp_enqueue_style(
            'material-design-icons',
            ( is_ssl() ? 'https' : 'http' ) . '://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css'
        );
    }

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'front_js' ] );

        add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ], 5 );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 3 );

        // Elementor enqueue scripts.
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_enqueue_scripts' ] );
    }
}