<?php

/**
 * The Mega Menu Functionality.
*/

namespace PortunaAddon\Modules;

defined( 'ABSPATH' ) || exit;

class Mega_Menu {
    use \PortunaAddon\Traits\Singleton;

    public function admin_styles() {
        $is_menu = get_current_screen();

        if ( 'nav-menus' !== $is_menu->base ) {
            return;
        }

        // CSS.
        wp_register_style(
            'font-awesome-5-all',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
            false,
            ELEMENTOR_VERSION
        );

        wp_register_style(
            'font-awesome-4-shim',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/v4-shims.min.css',
            [],
            ELEMENTOR_VERSION
        );

        wp_enqueue_style( 'font-awesome-5-all' );
        wp_enqueue_style( 'font-awesome-4-shim' );
        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_style(
            'portuna-addon-selectize',
            plugin_dir_url( dirname( __FILE__ ) ) . 'mega-menu/assets/css/select.min.css',
            [],
            '0.13.3'
        );

        wp_enqueue_style(
            'portuna-addon-mega-menu',
            plugin_dir_url( dirname( __FILE__ ) ) . 'mega-menu/assets/css/edit-walker-menu.min.css',
            [],
            null
        );

        // JS.
        wp_enqueue_script( 'wp-color-picker' );

        wp_enqueue_script(
            'portuna-addon-selectize',
            plugin_dir_url( dirname( __FILE__ ) ) . 'mega-menu/assets/js/select.js',
            [],
            '0.13.3',
        );

        wp_enqueue_script(
            'portuna-addon-mega-menu',
            plugin_dir_url( dirname( __FILE__ ) ) . 'mega-menu/assets/js/edit-walker-menu.min.js',
            [ 'jquery' ],
            null,
            true
        );

        wp_localize_script(
            'portuna-addon-mega-menu',
            'customIcon',
            [
                'rest_url'  => get_rest_url() . 'portuna/v1/',
                'settings'  => [
                    'nonce' => wp_create_nonce( 'update-menu-item' ),
                ],
            ]
        );

        wp_enqueue_media();
    }

    public function mega_menu_wrap() {
        $is_menu = get_current_screen();

        if ( 'nav-menus' !== $is_menu->base ) {
            return;
        }

        echo "<div class='portuna-mega-menu-wrapper'><div class='portuna-mega-menu-popup'></div><div class='portuna-mega-menu-popup-close'></div></div>";
    }

    public function wp_post_get_html( $item_id ) {
        $thumbnail_id = get_post_thumbnail_id( $item_id );

        $content      = sprintf(
            '<label for="edit-menu-item-icon-custom-%1$s">%2$s</label><br /><a data-id="%1$s" class="portuna-addon-set-custom-icon">%3$s</a>%4$s<input class="portuna-addon-icon-src" name="menu-item-icon-custom[%1$s]" type="hidden" value="%1$s" />',
            $item_id,
            esc_html__( 'Custom Icon', 'portuna-addon' ),
            $thumbnail_id ? wp_get_attachment_image( $thumbnail_id ) : esc_html__( '+', 'portuna-addon' ),
            $thumbnail_id ? '<a class="portuna-addon-reset-custom-icon" href="#"></a>' : '',
        );

        return $content;
    }

    /**
     * Set thumbnail via ajax action.
     */
    public function menu_set_thumbnail() {
        $json    = ! empty( $_REQUEST[ 'json' ] );
        $post_ID = intval( $_POST[ 'post_id' ] );

        if ( ! current_user_can( 'edit_post', $post_ID ) ) {
            wp_die( - 1 );
        }

        $thumbnail_id = intval( $_POST[ 'thumbnail_id' ] );

        check_ajax_referer( 'update-menu-item' );

        if ( $thumbnail_id == '-1' ) {
            $success = delete_post_thumbnail( $post_ID );
        } else {
            $success = set_post_thumbnail( $post_ID, $thumbnail_id );
        }

        if ( $success ) {
            $return = $this->wp_post_get_html( $post_ID );
            $json ? wp_send_json_success( $return ) : wp_die( $return );
        }

        wp_die( 0 );
    }

    public function add_custom_fields() {
        return '\\PortunaAddon\\Modules\\Mega_Menu_Edit_Walker';
    }

    /**
     * Add custom fields to $item nav object in order to be used in custom Walker.
     *
     * @param object $menu_item The menu item object.
     *
     * @return object The menu item.
     */
    public function setup_nav_menu_item( $menu_item ) {
        $menu_item->icon        = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
        $menu_item->is_megamenu = boolval( get_post_meta( $menu_item->ID, '_menu_item_is_megamenu', true ) );

        return $menu_item;
    }

    /**
     * Add the custom megamenu fields menu item data to fields in database.
     *
     * @param string|int $menu_id         The menu ID.
     * @param string|int $menu_item_db_id The menu ID from the db.
     * @param array      $args            The arguments array.
     */
    public function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
        extract( shortcode_atts( [
            'mega_menu_id'  =>  null,
        ], get_post_meta( $menu_item_db_id, 'portuna-addon-menu-item', true ) ) );

        $builder_post_title = 'widgets-content-editor-' . $menu_item_db_id;
        $builder_post_id    = get_page_by_title( $builder_post_title, OBJECT, 'portuna_content' );

        $custom_field_args = [
            'menu_type_icon'        =>  isset( $_REQUEST[ 'menu-item-icon-type' ][ $menu_item_db_id ] ) ? $_REQUEST[ 'menu-item-icon-type' ][ $menu_item_db_id ] : 'icon_none',
            'menu_icon'             =>  isset( $_REQUEST[ 'menu-item-icon' ][ $menu_item_db_id ] ) ? $_REQUEST[ 'menu-item-icon' ][ $menu_item_db_id ] : 'fa fa-500px',
            'menu_icon_custom'      =>  isset( $_REQUEST[ 'menu-item-icon-custom' ][ $menu_item_db_id ] ) ? $_REQUEST[ 'menu-item-icon-custom' ][ $menu_item_db_id ] : '',
            'menu_icon_color'       =>  isset( $_REQUEST[ 'menu-item-icon-color' ][ $menu_item_db_id ] ) ? $_REQUEST[ 'menu-item-icon-color' ][ $menu_item_db_id ] : '',
            'menu_badge_text'       =>  isset( $_REQUEST[ 'menu-item-badge-text' ][ $menu_item_db_id ] ) ? $_REQUEST[ 'menu-item-badge-text' ][ $menu_item_db_id ] : '',
            'menu_badge_text'       =>  isset( $_REQUEST[ 'menu-item-badge-text' ][ $menu_item_db_id ] ) ? $_REQUEST[ 'menu-item-badge-text' ][ $menu_item_db_id ] : '',
            'menu_badge_color'      =>  isset( $_REQUEST[ 'menu-item-badge-color' ][ $menu_item_db_id ] ) ? $_REQUEST[ 'menu-item-badge-color' ][ $menu_item_db_id ] : '',
            'menu_badge_bgcolor'    =>  isset( $_REQUEST[ 'menu-item-badge-bgcolor' ][ $menu_item_db_id ] ) ? $_REQUEST[ 'menu-item-badge-bgcolor' ][ $menu_item_db_id ] : '',
            'mega_menu_checkbox'    =>  isset( $_REQUEST[ 'menu-item-mega-menu' ][ $menu_item_db_id ] ) ? boolval( $_REQUEST[ 'menu-item-mega-menu' ][ $menu_item_db_id ] ) : false,
            'mega_menu_id'          => $builder_post_id
        ];

        update_post_meta( $menu_item_db_id, 'portuna-addon-menu-item', $custom_field_args );
    }

    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
        add_action( 'admin_footer', [ $this, 'mega_menu_wrap' ] );
        add_action( 'wp_ajax_portuna-addon-menu-set-thumbnail', [ $this, 'menu_set_thumbnail' ] );

        //add_filter( 'wp_setup_nav_menu_item', [ $this, 'setup_nav_menu_item' ], 110 );
        add_filter( 'wp_edit_nav_menu_walker', [ $this, 'add_custom_fields' ], 110 );

        if ( ! is_customize_preview() ) {
            add_action( 'wp_update_nav_menu_item', [ $this, 'update_custom_nav_fields' ], 110, 3 );
        }
    }
}