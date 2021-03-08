<?php

// Server side render.
extract( $args );

$this->add_render_attribute( 'wrapper', 'class', 'portuna-addon--mega-menu--layout1' );

if ( isset( $menu_type_side_position ) ) {
    $this->add_render_attribute( 'wrapper', 'class', sprintf( 'portuna-addon--mega-menu--layout1-%s', $menu_type_side_position ) );
}

$nav_settings = [
    'menu'              => $menu_list,
    'menu_class'        => '',
    'container_class'   => 'portuna-addon--mega-menu--content',
    'items_wrap'        => '<ul class="portuna-addon-menu-items portuna-addon-top-menu">%3$s</ul>',
    'before'            => '',
    'after'             => '',
    'walker'            => new PortunaAddon\Modules\Mega_Menu_Nav_Walker( $args, true ),
    'echo'              => false,
    'fallback_cb'       => false
];

?>

<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
    <?php echo wp_nav_menu( $nav_settings ); ?>
</div>