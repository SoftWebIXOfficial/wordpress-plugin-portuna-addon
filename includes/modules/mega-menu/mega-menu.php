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

        wp_enqueue_scripts();
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
            'mega_menu_id'  =>  false,
        ], get_post_meta( $menu_item_db_id, 'portuna-addon-menu', true ) ) );

        $custom_field_args = [
            'mega_menu_checkbox' =>  isset( $_REQUEST[ 'menu-item-mega-menu' ][ $menu_item_db_id ] ) ? boolval( $_REQUEST[ 'menu-item-mega-menu' ][ $menu_item_db_id ] ) : false,
        ];

        update_post_meta( $menu_item_db_id, 'portuna-addon-menu', $custom_field_args );
    }

    public function __construct() {
        //add_action( 'admin_enqueue_scripts', 'admin_styles', 10 );
        //add_filter( 'wp_setup_nav_menu_item', 'setup_nav_menu_item', 110 );
        add_filter( 'wp_edit_nav_menu_walker', [ $this, 'add_custom_fields' ], 10 );

        if ( ! is_customize_preview() ) {
            add_action( 'wp_update_nav_menu_item', [ $this, 'update_custom_nav_fields' ], 10, 3 );
        }
    }
}