<?php

namespace PortunaAddon\Controls;

defined( 'ABSPATH' ) || exit;

use \Elementor\Base_Data_Control;

class Select_Image extends Base_Data_Control {
    /**
     * Set control type.
     */
    public function get_type() {
        return 'select_image';
    }

    /**
     * Set scripts.
     */
    public function enqueue() {
        // Styles.
        wp_register_style(
            'portuna-addon-controls-admin-select-image-style',
            plugins_url( 'portuna-addon/assets/' ) . 'css/admin-select-image.min.css',
            [],
            null
        );
        wp_enqueue_style(
            'portuna-addon-controls-admin-select-image-style'
        );

        // Scripts.
        wp_register_script(
            'portuna-addon-controls-admin-select-image-script',
            plugins_url( 'portuna-addon/assets/' ) . 'js/admin-select-image.min.js',
            [ 'jquery' ],
            null,
            true
        );
        wp_enqueue_script(
            'portuna-addon-controls-admin-select-image-script'
        );
    }

    /**
     * Set default settings.
     */
    protected function get_default_settings() {
        return [
            'label_block'                   => true,
            'options'                       => [],
        ];
    }

    public function content_template() {
        $control_uid = $this->get_control_uid( '{{value}}' );

        ?>

            <div class="elementor-control-field">
                <label class="elementor-control-title">{{{ data.label }}}</label>
                <div class="elementor-control-image-selector-wrapper">
                    <# _.each( data.options, function( options, value ) { #>
                        <div style="width: 100%;">
                            <input id="<?php echo esc_attr( $control_uid ); ?>" type="radio" name="elementor-image-selector-{{ data.name }}-{{ data._cid }}" value="{{ value }}" data-setting="{{ data.name }}" />
                            <label class="elementor-image-selector-label tooltip-target" for="<?php echo $control_uid; ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
                                <img src="{{ options.url }}" alt="{{ options.title }}">
                                <span class="elementor-screen-only">{{{ options.title }}}</span>
                            </label>
                        </div>
                    <# } ); #>
                </div>
            </div>

            <# if ( data.description ) { #>
                <div class="elementor-control-field-description">{{{ data.description }}}</div>
            <# } #>

        <?php
    }

}