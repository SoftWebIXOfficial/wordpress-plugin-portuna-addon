<?php

use \Elementor\Icons_Manager;

$this->add_render_attribute( 'wrapper', 'class', 'portuna-addon--creative-button--layout1' );

$button_attr_id      = ( $button_id != '' ) ? 'id="' . esc_attr( $button_id ) . '"' : null;
$button_attr_href    = ( $button_url != '' ) ? 'href="' . esc_url( $button_url[ 'url' ] ) . '"' : null;
$button_unique_style = ( $button_border_type === 'unique' ) ? 'portuna-addon--creative-button-link--unique' : null;
$button_border_style = ( $button_border_type !== 'unique' ) ? 'style="border-style: ' . esc_attr( $button_border_type ) . ';"' : null;

$migrated         = isset( $settings[ '__fa4_migrated' ][ 'button_icons' ] );
$is_new           = empty( $button_icon );

?>

<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
    <div class="portuna-addon--creative-button-content">
        <a class="portuna-addon--creative-button-link <?php echo esc_attr( $button_unique_style ); ?> " <?php echo $button_attr_id .' '. $button_attr_href . ' ' . $button_border_style; ?>>
            <?php
                if ( $is_new || $migrated ) {
                    // new icon
                    Icons_Manager::render_icon( $button_icons, [ 'aria-hidden' => 'true', 'class' => 'portuna-addon--creative-button-icon' ] );
                } else {
                    ?>
                        <i class="<?php echo esc_attr( $button_icon ); ?> portuna-addon--creative-button-icon" aria-hidden="true"></i>
                    <?php
                }
            ?>
            <?php echo esc_html( $button_label_text ); ?>
        </a>
    </div>
</div>