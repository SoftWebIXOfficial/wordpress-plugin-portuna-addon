<?php

use \Elementor\Icons_Manager;

// Server side render.
extract( $args );

$this->add_render_attribute( 'wrapper', 'class', 'portuna-addon--mega-menu--layout1' );

if ( isset( $menu_type_side_position ) ) {
    $this->add_render_attribute( 'wrapper', 'class', sprintf( 'portuna-addon--mega-menu--layout1-%s', $menu_type_side_position ) );
}

if ( $device_breakpoint_hamburger === 'yes' ) {
    $close_icon_migrate = isset( $settings[ '__fa4_migrated' ][ 'hamburger_close_icons' ] );
    $close_icon_new     = empty( $close_icon_migrate );
    $close_btn          = '';

    $close_btn .= '<button class="portuna-addon--mega-menu--toggle close-menu">';
        if ( $close_icon_new || $close_icon_migrate ) {
            // new icon
            ob_start();
                Icons_Manager::render_icon( $hamburger_close_icons, [ 'aria-hidden' => 'true', 'class' => 'icon' ] );
            $close_icon = ob_get_clean();

            $close_btn .= $close_icon;
        } else {
            $close_btn .= '<i class="' . esc_attr( $hamburger_close_icons ) . ' icon" aria-hidden="true"></i>';
        }
    $close_btn .= '</button>';
}

$nav_settings = [
    'menu'              => $menu_list,
    'menu_class'        => '',
    'container_class'   => 'portuna-addon--mega-menu--content',
    'items_wrap'        => $close_btn . '<ul class="portuna-addon-menu-items portuna-addon-top-menu">%3$s</ul>',
    'before'            => '',
    'after'             => '',
    'walker'            => new PortunaAddon\Modules\Mega_Menu_Nav_Walker( $args, true ),
    'echo'              => false,
    'fallback_cb'       => false
];

?>

<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
    <?php if ( $device_breakpoint_hamburger === 'yes' ) { ?>
        <button class="portuna-addon--mega-menu--toggle open-menu">
            <span class="portuna-addon--mega-menu--toggle-icon">
                <?php
                    $open_icon_migrate = isset( $settings[ '__fa4_migrated' ][ 'hamburger_open_icons' ] );
                    $open_icon_new     = empty( $open_icon_migrate );

                    if ( $open_icon_new || $open_icon_migrate ) {
                        // new icon
                        Icons_Manager::render_icon( $hamburger_open_icons, [ 'aria-hidden' => 'true', 'class' => 'icon' ] );
                    } else {
                        ?>
                            <i class="<?php echo esc_attr( $hamburger_open_icons ); ?> icon" aria-hidden="true"></i>
                        <?php
                    }
                ?>
            </span>
        </button>
    <?php } ?>
    <?php echo wp_nav_menu( $nav_settings ); ?>
</div>