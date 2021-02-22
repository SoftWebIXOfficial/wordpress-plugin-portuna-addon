<?php

namespace PortunaAddon\Helpers;

defined( 'ABSPATH' ) || exit;

class Utils {
    use \PortunaAddon\Traits\Singleton;

    public static function init_view() {
        add_action( 'elementor/editor/after_enqueue_styles', [ __CLASS__, 'view_admin_elementor_area' ] );
    }

    public static function view_admin_elementor_area() {
        ob_start();

        ?>
            <div class="portuna-addon-modal">
                <?php include PORTUNA_PLUGIN_DIR . 'includes/views/admin-elementor-area.php'; ?>
            </div>
        <?php

        $output = ob_get_contents();
        ob_end_clean();

        echo self::render( $output );
    }
    
    public static function parse_widget_content( $content, $widget_key, $i = 1 ) {
        $key      = ( $content == '' ) ? $widget_key : $content;
        $base_key = explode( '***', $key );
        $base_key = $base_key[0];

        ob_start();

        ?>
            <div class="widgetarea_warper widgetarea_warper_editable" data-portuna-key="<?php echo esc_attr( $base_key ); ?>" data-portuna-id="<?php echo esc_attr( $i ); ?>">
                <div class="widgetarea_warper_edit" data-portuna-key="<?php echo esc_attr( $base_key ); ?>" data-portuna-id="<?php echo esc_attr( $i ); ?>">
                    <i class="eicon-custom" aria-hidden="true"></i>
                    <span class="elementor-screen-only"><?php esc_html_e( 'Edit', 'portuna-addon' ); ?></span>
                </div>
                <div class="widgetarea_warper_close" data-portuna-key="<?php echo esc_attr( $base_key ); ?>" data-portuna-id="<?php echo esc_attr( $i ); ?>">
                    <i class="eicon-editor-close" aria-hidden="true"></i>
                    <span class="elementor-screen-only"><?php esc_html_e( 'Close', 'portuna-addon' ); ?></span>
                </div>

                <div class="elementor_widget_container">
                    <?php
                        $post_title = 'widgets-content-widget-' . $base_key . '-' . $i;
                        $post       = get_page_by_title( $post_title, OBJECT, 'portuna_content' );
                        $elementor  = \Elementor\Plugin::instance();

                        if ( isset( $post->ID ) ) {
                            echo str_replace( '#elementor', '', self::render_tab_content( $elementor->frontend->get_builder_content_for_display( $post->ID ), $post->ID ) );
                        } else {
                            echo esc_html__( 'Click Here to Add Content', 'portuna-addon' );
                        }
                    ?>

                </div>
            </div>
        <?php

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
    
    public static function render( $content ) {
        return $content;
    }

    public static function render_tab_content( $content, $id ) {
        return str_replace( '.elementor-' . $id . ' ', '#elementor .elementor-' . $id . ' ', $content );
    }

    /**
     * Get carousel params
     *
     * @param array $args all arguments
     * @param string $prefix prefix
     * @param string $default if user don't choose option Change slider options
     * @param array $additional other options (some hardcode)
     *
     * @return mixed data-attributes for swiper init
     */
    public static function get_carousel_data( $args = [], $prefix = '', $additional = [] ) {
        $params_arr = [];

        $all_params = [
            'effect',
            'loop',
            'autoplay',
            'speed',
            'simulate_touch',
            'initial_slide',
            'direction',
            'overflow',
            'centeredSlides',
            'lazy',
            'slides',
            'slides_lg',
            'slides_md',
            'slides_sm',
            'slides_xs',
            'spaces',
            'spaces_lg',
            'spaces_md',
            'spaces_sm',
            'spaces_xs'
        ];

        foreach ( $all_params as $param ) {
            if ( isset( $args[ $prefix . $param ] ) && $args[ $prefix . $param ] ) {
                $params_arr[ $param ] = $args[ $prefix . $param ];
            }
        }

        $params_arr = wp_parse_args( $additional, $params_arr );

        array_walk( $params_arr, function ( $val, $key ) use ( &$result ) {
            $result .= "data-$key=$val ";
        } );

        return $result;
    }
}