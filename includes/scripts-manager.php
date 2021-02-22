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
            'portuna-swiper',
            plugins_url( 'portuna-addon/assets/' ) . 'vendors/swiper/css/swiper.min.css',
            [],
            '6.4.15'
        );
    }

    public function register_scripts() {
        // Vendors
        wp_enqueue_script(
            'portuna-swiper',
            plugins_url( 'portuna-addon/assets/' ) . 'vendors/swiper/js/swiper.min.js',
            [],
            '6.4.15',
            true
        );
    }

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'front_js' ] );

        add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ], 5 );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 3 );
    }
}