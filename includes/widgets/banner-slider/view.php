<?php

use \PortunaAddon\Helpers\Utils;

// Server side render.
extract( $args );

$carousel_params = [
    'loop'  => $slide_loop == 'yes' ? 1 : 0,
];

$carousel_data = Utils::get_carousel_data( $carousel_params, '' );

// $carousel_params = [
//     'include'   => [ $slide_nav ],
// ];

// $carousel_params = apply_filters( 'portuna-addon-slider-settings', $carousel_params );

// print_r( $carousel_params );

$this->add_render_attribute( 'wrapper', 'class', 'portuna-addon--banner-slider--layout1' );

//\PortunaAddon\Helpers\Utils::parse_widget_content( $portuna_banner_slides_elementor, $this->get_id(), $key );

// Slide Items.
$items = $slide_item;

?>

<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
    <div class="swiper-container" <?php echo esc_attr( $carousel_data ); ?>>
        <div class="swiper-wrapper">
            <?php foreach ( $items as $key => $item ) : ?>
                <div class="swiper-slide">
                    <?php echo $key; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>