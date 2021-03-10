<?php

use \PortunaAddon\Helpers\Utils;
use \Elementor\Icons_Manager;

// Server side render.
extract( $args );

$carousel_params = [
    'autoplay'             => $slide_autoplay,
    'autoplay-delay'       => ! empty( $slide_autoplay ) ? $slide_pause_delay : null,
    'autoplay-interaction' => $slide_pause_interaction,
    'loop'                 => $slide_loop,
    'simulate-touch'       => $slide_simulate_touch,
    'effect'               => ! empty( $slide_effect ) ? $slide_effect : 'slide',
    'speed'                => $slide_effect_speed,
    'direction'            => $slide_direction,
    'pagination-type'      => $slide_pagination_type,
    'center-slide'         => $slide_center,
    'slides-preview'       => $slide_preview,
    'slides-space'         => $slide_space,
];

$carousel_data = Utils::get_carousel_data( $carousel_params, '' );

$this->add_render_attribute( 'wrapper', 'class', 'portuna-addon--banner-slider--layout1' );

$arrow_custom_pro = $slide_arrows_type === 'custom-pro' ? true : false;

//\PortunaAddon\Helpers\Utils::parse_widget_content( $portuna_banner_slides_elementor, $this->get_id(), $key );

// Slide Items.
$items = $slide_item;

?>

<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
    <div class="swiper">
        <div class="swiper-container" <?php echo esc_attr( $carousel_data ); ?>>
            <div class="swiper-wrapper">
                <?php foreach ( $items as $key => $item ) : ?>
                    <div class="swiper-slide">
                        <?php echo \PortunaAddon\Helpers\Utils::parse_widget_content( $portuna_banner_slides_elementor, $this->get_id(), $key ); ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Add Pagination -->
            <?php if ( $slide_nav === 'both' || $slide_nav === 'dots' ) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
        </div>

        <!-- Add Arrows -->
        <?php if ( $slide_nav === 'both' || $slide_nav === 'arrows' ) :
            $arrow_icon_prev = null;
            $arrow_icon_next = null;

            if ( $arrow_custom_pro ) :
                $arrow_prev_migrated = isset( $slide_arrow_prev_icons_pro[ '__fa4_migrated' ] );
                $arrow_prev_is_new   = empty( $slide_arrow_prev_icon_pro );

                $arrow_next_migrated = isset( $args[ '__fa4_migrated' ][ 'slide_arrow_next_icons_pro' ] );
                $arrow_next_is_new   = empty( $args[ 'slide_arrow_next_icon_pro' ] );

                if ( $arrow_prev_migrated || $arrow_prev_is_new ) :
                    ob_start();
                        Icons_Manager::render_icon( $slide_arrow_prev_icons_pro, [ 'aria-hidden' => 'true' ] );
                    $arrow_icon_prev = ob_get_clean();
                else :
                    $arrow_icon_prev = "<i class='" . esc_attr( $slide_arrow_prev_icon_pro ) . "'</i>";
                endif;

                if ( $arrow_next_migrated || $arrow_next_is_new ) :
                    ob_start();
                        Icons_Manager::render_icon( $args[ 'slide_arrow_next_icons_pro' ], [ 'aria-hidden' => 'true' ] );
                    $arrow_icon_next = ob_get_clean();
                else :
                    $arrow_icon_next = "<i class='" . esc_attr( $slide_arrow_next_icon_pro ) . "'</i>";
                endif;

                if ( $slide_arrows_distance_pro === 'together' ) :
                    if ( $slide_arrows_separator_type_pro === 'icon' ) :
                        $separator_migrated = isset( $slide_arrows_separator_icons_pro[ '__fa4_migrated' ] );
                        $separator_is_new   = empty( $slide_arrows_separator_icon_pro );

                        if ( $separator_migrated || $separator_is_new ) :
                            ob_start();
                                Icons_Manager::render_icon( $slide_arrows_separator_icons_pro, [ 'aria-hidden' => 'true' ] );
                            $separator_icon = ob_get_clean();
                        else :
                            $separator_icon = "<i class='" . esc_attr( $slide_arrows_separator_icon_pro ) . "'</i>";
                        endif;
                    else :
                        $separator_icon = "<span class='swiper-arrow-separator'>" . esc_html( $slide_arrows_separator_text_pro ) . "</span>";
                    endif;
                endif;
            endif;
        ?>
            <?php echo $arrow_custom_pro ? $slide_arrows_distance_pro === 'together' ? '<div class="swiper-arrows-together">' : null : null ?>
                <div class="swiper-button-prev <?php echo $arrow_custom_pro == true ? esc_attr( 'swiper-button-custom-prev' ) : '' ?> "><?php echo $arrow_icon_prev; ?></div>
                <?php
                    echo $arrow_custom_pro ? $slide_arrows_distance_pro === 'together' ? $separator_icon : null : null;
                ?>
                <div class="swiper-button-next <?php echo $arrow_custom_pro == true ? esc_attr( 'swiper-button-custom-next' ) : '' ?>"><?php echo $arrow_icon_next; ?></div>
            <?php echo $arrow_custom_pro ? $slide_arrows_distance_pro === 'together' ? '</div>' : null : null ?>
        <?php endif; ?>
    </div>
</div>