<?php

use \PortunaAddon\Helpers\Utils;

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
                        <h1 style="text-align: center;"><?php echo $key; ?></h1>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Add Pagination -->
            <?php if ( $slide_nav === 'both' || $slide_nav === 'dots' ) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
        </div>

        <!-- Add Arrows -->
        <?php if ( $slide_nav === 'both' || $slide_nav === 'arrows' ) : ?>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        <?php endif; ?>
    </div>
</div>