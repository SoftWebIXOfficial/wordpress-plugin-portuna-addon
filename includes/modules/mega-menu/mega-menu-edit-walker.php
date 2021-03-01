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
            'menu_type_icon'        =>  'icon_none',
            'menu_icon'             =>  '',
            'menu_icon_custom'      =>  '',
            'menu_icon_color'       =>  '',
            'menu_badge_text'       =>  '',
            'menu_badge_color'      =>  '',
            'menu_badge_bgcolor'    =>  '',
            'mega_menu_checkbox'    =>  false,
        ], get_post_meta( $item_id, 'portuna-addon-menu-item', true ) ) );

        ob_start();

        ?>
            <p class="description description-wide">
                <label for="edit-menu-item-icon-type-<?php echo esc_attr( $item_id ); ?>">
                    <?php esc_html_e( 'Select Icon Type', 'portuna-addon' ); ?>
                    <select class="widefat portuna-addon-icon-type" id="edit-menu-item-icon-type-<?php echo esc_attr( $item_id ); ?>" name="menu-item-icon-type[<?php echo esc_attr( $item_id ); ?>]">
                        <option value="icon_none" <?php echo ( esc_attr( $menu_type_icon ) === 'icon_none' ? ' selected' : '' ); ?> >
                            <?php esc_html_e( 'None', 'portuna-addon' ); ?>
                        </option>
                        <option value="icon_lib" <?php echo ( esc_attr( $menu_type_icon ) === 'icon_lib' ? ' selected' : '' ); ?> >
                            <?php esc_html_e( 'Icon Library', 'portuna-addon' ); ?>
                        </option>
                        <option value="icon_custom" <?php echo ( esc_attr( $menu_type_icon ) === 'icon_custom' ? ' selected' : '' ); ?> >
                            <?php esc_html_e( 'Custom Icon', 'portuna-addon' ); ?>
                        </option>
                    </select>
                </label>
            </p>

            <p class="field-is-icon-lib description description-thin">
                <label for="edit-menu-item-icon-<?php echo esc_attr( $item_id ); ?>">
                    <?php esc_html_e( 'Icon Library', 'portuna-addon' ); ?>
                    <select class="widefat portuna-addon-select-icon" id="edit-menu-item-icon-<?php echo esc_attr( $item_id ); ?>" name="menu-item-icon[<?php echo esc_attr( $item_id ); ?>]">
                        <?php
                            $icons_lib = \Elementor\Control_Icon::get_icons();

                            foreach ( $icons_lib as $key => $icon ) :
                                ?>
                                    <option value="<?php echo esc_attr( $key ); ?>" <?php echo ( esc_attr( $key ) === esc_attr( $menu_icon ) ? ' selected' : '' ); ?> ><?php echo esc_attr( $icon ); ?></option>
                                <?php
                            endforeach;
                        ?>
                    </select>
                </label>
            </p>

            <p id="field-is-icon-cus-<?php echo esc_attr( $item_id ); ?>" class="field-is-icon-cus description description-thin" style="margin-right: 10px;">
                <label for="edit-menu-item-icon-custom-<?php echo esc_attr( $item_id ); ?>">
                    <?php esc_html_e( 'Custom Icon', 'portuna-addon' ); ?>
                </label><br/>
                <?php
                    $thumbnail_id = get_post_thumbnail_id( $item_id );

                    $content      = sprintf(
                        '<a class="portuna-addon-set-custom-icon" href="#" data-id="%s">%s</a>%s',
                        $item_id,
                        $thumbnail_id ? wp_get_attachment_image( $thumbnail_id ) : esc_html__( '+', 'portuna-addon' ),
                        $thumbnail_id ? '<a class="portuna-addon-reset-custom-icon" href="#"></a>' : '',
                    );

                    echo $content;
                ?>
                <input class="portuna-addon-icon-src" name="menu-item-icon-custom[<?php echo esc_attr( $item_id ); ?>]" type="hidden" value="<?php echo esc_attr( $item_id ); ?>" />
            </p>

            <p class="field-is-icon-color description description-thin">
                <label> <?php esc_html_e( 'Icon Color', 'portuna-addon' ); ?> </label></br>
                <input type="text" class="portuna-addon-color" id="edit-menu-item-icon-color-<?php echo esc_attr( $item_id ); ?>" name="menu-item-icon-color[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_icon_color ); ?>" />
            </p>

            <p class="description description-wide">
                <label for="edit-menu-item-badge-text-<?php echo esc_attr( $item_id ); ?>">
                    <?php esc_html_e( 'Badge Text', 'portuna-addon' ); ?></br>
                    <input style="width: 100%;" type="text" class="portuna-addon-badge-text" id="edit-menu-item-badge-text-<?php echo esc_attr( $item_id ); ?>" name="menu-item-badge-text[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_badge_text ); ?>" />
                </label>
            </p>

            <p class="description description-thin">
                <label> <?php esc_html_e( 'Bagde Text Color', 'portuna-addon' ); ?> </label></br>
                <input type="text" class="portuna-addon-color" id="edit-menu-item-badge-color-<?php echo esc_attr( $item_id ); ?>" name="menu-item-badge-color[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_badge_color ); ?>" />
            </p>

            <p class="description description-thin">
                <label> <?php esc_html_e( 'Bagde Background Color', 'portuna-addon' ); ?> </label></br>
                <input type="text" class="portuna-addon-color" id="edit-menu-item-badge-bgcolor-<?php echo esc_attr( $item_id ); ?>" name="menu-item-badge-bgcolor[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_badge_bgcolor ); ?>" />
            </p>

            <p class="field-is-mega-menu description-wide">
                <label for="edit-menu-item-mega-menu-<?php echo esc_attr( $item_id ); ?>">
                    <input class="portuna-addon-mega-menu-checkbox" type="checkbox" id="edit-menu-item-mega-menu-<?php echo esc_attr( $item_id ); ?>" value="<?php echo esc_attr( $item_id ); ?>" name="menu-item-mega-menu[<?php echo esc_attr( $item_id ); ?>]" <?php checked( $mega_menu_checkbox, true ); ?> />
                    <?php esc_html_e( 'Mega Menu Enable', 'portuna-addon' ); ?>
                </label>
            </p>

            <p class="description description-wide">
                <button type="button" class="button portuna-addon-mega-menu-edit-btn" data-item-id="<?php echo esc_attr( $item_id ); ?>">
                    <?php esc_html_e( 'Edit Mega Menu Content', 'portuna-addon' ); ?>
                </button>
            </p>
        <?php

        return ob_get_clean();
    }
}