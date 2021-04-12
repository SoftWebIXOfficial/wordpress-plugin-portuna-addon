<?php

namespace PortunaAddon;

defined( 'ABSPATH' ) || exit;

class Base {
    use Traits\Singleton;

    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION       = '5.6';

    private function init_class() {
        include_once PORTUNA_PLUGIN_PATH . 'includes/core/elementor-cpt/init.php';
    }

    public function i18n() {
        load_plugin_textdomain( 'portuna-addon', false, PORTUNA_PLUGIN_PATH . 'languages/' );
    }
   
    public function admin_notice_missing_main_plugin() {
        if ( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'portuna-addon' ),
            '<strong>' . esc_html__( 'Portuna', 'portuna-addon' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'portuna-addon' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'portuna-addon' ),
            '<strong>' . esc_html__( 'Portuna', 'portuna-addon' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'portuna-addon' ) . '</strong>',
             self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'portuna-addon' ),
            '<strong>' . esc_html__( 'Portuna', 'portuna-addon' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'portuna-addon' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function check_elementor_plugin() {
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );

            return false;
        }
    }

    public function minimum_elementor_version() {
        // Check for required PHP version.
        if ( version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '<=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );

            return false;
        }
    }

    public function minimum_php_version() {
        // Check for required PHP version.
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );

            return false;
        }
    }

    public function is_compatible() {
        $this->check_elementor_plugin();
        $this->minimum_elementor_version();
        $this->minimum_php_version();

        return true;
    }

    protected function add_actions() {
        if ( $this->is_compatible() ) {
            add_action( 'elementor/init', [ $this, 'elementor_init' ] );

//             // Custom contact methods.
//             add_filter( 'user_contactmethods', array( $this, 'contact_methods' ), 10, 1 );
//             // register elementor category
            add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );

            Helpers\WidgetsManager::instance();
            Helpers\ControlsManager::instance();

            // API
            new Api\Rest_Api();

            // ***
            \PortunaAddon\ScriptsManager::instance();
            AdminPage::instance();
        }
    }

    public function elementor_init() {
        include_once PORTUNA_PLUGIN_PATH . 'includes/core/elementor-cpt/init.php';

        new \PortunaAddon\Core\Cpt\Init();
        new \PortunaAddon\Modules\Mega_Menu();
    }

    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'portuna-addons-category',
            [
               'title' => __( 'Portuna Addons', 'portuna-addon' ),
               'icon' => '',
            ]
        );
    }

    public function __construct() {
        //$this->i18n();

        //$this->init_class();
        $this->add_actions();

        //Payment button
        //Payment card verification url scheme B(host-to-host) https://docs.fondy.eu/docs/page/11/
        $callback        = file_get_contents( 'php://input' );
        $callaback_object = json_decode($callback);

        echo 'This is callback data - ' . $callaback_object;

        global $_GET;
        var_dump($_GET);
    }
}