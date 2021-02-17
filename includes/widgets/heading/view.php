<?php

// Server side render.

extract( $args );

$this->add_render_attribute( 'wrapper', 'class', 'portuna-addon--advanced-heading--layout1' );

// print_r( $args );

?>

<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
    <div class="portuna-addon--advanced-heading--content">
        <?php
            if ( $portuna_title_switch === 'yes' ) {
                echo '<' . $portuna_title_tag . ' class="portuna-addon--advanced-heading--content-title">' . ( ! empty( $portuna_title_link[ 'url' ] ) ? '<a href="' . esc_url( $portuna_title_link[ 'url' ] ) . '">' . esc_html( $portuna_title )  . '</a>' : esc_html( $portuna_title ) ) . '</' . $portuna_title_tag . '>';
            }
        ?>
        <?php
            if ( $portuna_subtitle_switch === 'yes' ) {
                $shadow_square = $portuna_addon_items_decor_type === 'shadow_square';

                echo '<' . $portuna_subtitle_tag . ' class="portuna-addon--advanced-heading--content-subtitle">' . ( ! empty( $portuna_subtitle_link[ 'url' ] ) ? '<a href="' . esc_url( $portuna_subtitle_link[ 'url' ] ) . '">' . ( $shadow_square ? '<span class="portuna-addon--advanced-heading--content-subtitle-square portuna-addon--advanced-heading--content-subtitle-square-' . esc_attr( $portuna_subtitle_square_hr_position ) . ' portuna-addon--advanced-heading--content-subtitle-square-' . esc_attr( $portuna_subtitle_square_vr_position ) . '">' . esc_html( $portuna_subtitle ) . '</span>' : esc_html( $portuna_subtitle ) )  . '</a>' : ( $shadow_square ? '<span class="portuna-addon--advanced-heading--content-subtitle-square portuna-addon--advanced-heading--content-subtitle-square-' . esc_attr( $portuna_subtitle_square_hr_position ) . ' portuna-addon--advanced-heading--content-subtitle-square-' . esc_attr( $portuna_subtitle_square_vr_position ) . '">' . esc_html( $portuna_subtitle ) . '</span>' : esc_html( $portuna_subtitle ) ) ) . '</' . $portuna_subtitle_tag . '>';
            }
        ?>
    </div>
</div>