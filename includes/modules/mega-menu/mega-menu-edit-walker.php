<?php
/**
 * The Mega Menu Functionality.
*/

namespace PortunaAddon\Modules;

use Walker_Nav_Menu_Edit;

defined( 'ABSPATH' ) || exit;

class Mega_Menu_Edit_Walker extends Walker_Nav_Menu_Edit {
    use \PortunaAddon\Traits\Singleton;

    /**
     * Start the element output.
     *
     * We're injecting our custom fields after the div.submitbox
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   Menu item args.
     * @param int    $id     Nav menu ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
        $item_output = '';

        parent::start_el( $item_output, $item, $depth, $args, $id );

        $output .= preg_replace(
            // NOTE: Check this regex from time to time!
            '/(?=<(fieldset|p)[^>]+class="[^"]*field-move)/',
            $this->get_fields( $item, $depth, $args ),
            $item_output
        );
    }

    protected function get_fields( $item, $depth, $args = [] ) {
        $item_id = esc_attr( $item->ID );

        extract( shortcode_atts( [
            'mega_menu_checkbox'  =>  false,
        ], get_post_meta( $item_id, 'portuna-addon-menu', true ) ) );

        ob_start();

        ?>
            <p class="field-is-mega-menu description">
                <label for="edit-menu-item-mega-menu-<?php echo $item_id; ?>">
                    <input type="checkbox" id="edit-menu-item-mega-menu-<?php echo $item_id; ?>" value="<?php echo esc_attr( $item_id ); ?>" name="menu-item-mega-menu[<?php echo esc_attr( $item_id ); ?>]" <?php checked( $mega_menu_checkbox, true ); ?> />
                    <?php esc_html_e( 'Mega Menu Enable', 'portuna-addon' ); ?>
                </label>
            </p>

            <p class="description">
                <button type="button" class="button" data-item-id="<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Edit Mega Menu Content', 'portuna-addon' ); ?></button>
            </p>
        <?php

        return ob_get_clean();
    }
}