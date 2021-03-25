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
//         new Helpers\Ajax;

        add_action( 'admin_menu', [ $this, 'register_admin_panel_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_panel_enqueue_scripts' ], 100 );

        add_action( 'wp_ajax_' . self::PORTUNA_NONCE, [ $this, 'save_user_data' ] );
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
            __( 'Portuna Addon Settings', 'portuna-addon' ),
            __( 'Portuna Settings', 'portuna-addon' ),
            'manage_options',
            self::PORTUNA_SLUG,
            [ $this, 'admin_init' ],
            'dashicons-admin-tools',
            3.22
        );
    }

    public function admin_init() {
        $userData    = $this->options->get_option( 'user_data', [] );

        ?>
        	<div class="wrap">
        		<h2><?php echo get_admin_page_title() ?></h2>

        		<form action="" method="POST" id="portuna-addon-form">

        		    <!------------- Google API ----------->
        		    <div class="google-maps-api-settings">
                        <label><?php echo esc_html__( 'Google Map', 'portuna-addon' ); ?></label>
                        <input placeholder="AIzaSyA-10-OHpfss9XvUDWILmos62MnG_L4MYw" name="user_data[google_api_key]" value="<?php echo ( ! isset( $userData[ 'user_data' ][ 'google_api_key' ] ) ) ? '' : ( $userData[ 'user_data' ][ 'google_api_key' ] ) ?>" />
        		    </div>

        			<button disabled class="portuna-addon-form-save-btn" type="submit"><div></div><?php esc_html_e( 'Save Changes', 'simpli' ); ?></button>
        		</form>
        	</div>
        <?php
    }

}