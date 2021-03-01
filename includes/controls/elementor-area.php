<?php

namespace PortunaAddon\Controls;

defined( 'ABSPATH' ) || exit;

use \Elementor\Base_Data_Control;

class Elementor_Area extends Base_Data_Control {
    /**
     * Set control type.
     */
    public function get_type() {
        return 'elementor_area';
    }

    /**
     * Set scripts.
     */
    public function enqueue() {
        // Styles.
        wp_register_style(
            'portuna-addon-controls-admin-elementor-area-style',
            plugins_url( 'portuna-addon/assets/' ) . 'css/admin-elementor-area.min.css',
            [],
            null
        );
        wp_enqueue_style(
            'portuna-addon-controls-admin-elementor-area-style'
        );

        // Scripts.
        wp_register_script(
            'portuna-addon-controls-admin-elementor-area-script',
            plugins_url( 'portuna-addon/assets/' ) . 'js/admin-elementor-area.min.js',
            [ 'jquery' ],
            null,
            true
        );
        wp_enqueue_script(
            'portuna-addon-controls-admin-elementor-area-script'
        );
    }

    /**
     * Set default settings.
     */
     protected function get_default_settings() {
         return [
             'label_block'                   => true,
             'show_edit_button'              => false,
         ];
     }

     public function content_template() {
         $control_uid = $this->get_control_uid();

         ?>

             <div style="display: none;" class="elementor-control-field">
                 <label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
                 <div class="elementor-control-input-wrapper">
                     <input id="<?php echo esc_attr( $control_uid ); ?>" type="text" data-setting="{{ data.name }}" />
                 </div>
             </div>

             <# if ( data.description ) { #>
                 <div class="elementor-control-field-description">{{{ data.description }}}</div>
             <# } #>

         <?php
     }
}