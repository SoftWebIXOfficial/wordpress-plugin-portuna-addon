<?php

// Server side render.
extract( $args );

$this->add_render_attribute( 'wrapper', 'class', 'portuna-addon--banner-slider--layout1' );

?>

<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
    <div class="portuna-addon--banner-slider--content">
        <?php echo \PortunaAddon\Helpers\Utils::parse_widget_content( $portuna_banner_slides_elementor, $this->get_id(), 1 ); ?>
    </div>
</div>