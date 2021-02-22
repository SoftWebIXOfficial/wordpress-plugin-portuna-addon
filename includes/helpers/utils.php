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
}