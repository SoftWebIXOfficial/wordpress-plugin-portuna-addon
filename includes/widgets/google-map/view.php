<?php

use \PortunaAddon\Helpers\Utils;

extract( $args );

$this->add_render_attribute( 'wrapper', 'class', 'portuna-addon--google-map--layout1' );

$options     = \PortunaAddon\Helpers\Options::instance();
$userData    = $options->get_option( 'user_data', [] );
$hasApiKey   = ! empty( $userData[ 'user_data' ][ 'google_api_key' ] ) && '' != $userData[ 'user_data' ][ 'google_api_key' ];

$map_params  = [
    'type'            => isset( $portuna_google_map_type ) ? esc_attr( $portuna_google_map_type )    : '',
    'zoomLevel'       => isset( $portuna_map_zoom )        ? esc_attr( $portuna_map_zoom[ 'size' ] ) : '',
    'addressType'     => isset( $portuna_address_type )    ? esc_attr( $portuna_address_type )       : '',
    'addressName'     => isset( $portuna_map_address )     ? esc_attr( $portuna_map_address )        : '',
    'centerLat'       => isset( $portuna_latitude )        ? esc_attr( $portuna_latitude )           : '',
    'centerLng'       => isset( $portuna_longitude )       ? esc_attr( $portuna_longitude )          : '',
    'markerContent'   => isset( $portuna_marker_content )  ? esc_attr( $portuna_marker_content )     : '',
    'popupMaxWidth'   => isset( $portuna_marker_content_width )          ? esc_attr( $portuna_marker_content_width )             : '',
    'isPopupOpen'     => isset( $portuna_marker_content_opened_switch )  ? esc_attr( $portuna_marker_content_opened_switch )     : '',
    'isCustomIcon'    => isset( $portuna_marker_icon_switch )            ? esc_attr( $portuna_marker_icon_switch )               : '',
    'iconUrl'         => isset( $portuna_marker_icon )                   ? esc_attr( $portuna_marker_icon[ 'url' ] )             : '',
    'iconWidth'       => isset( $portuna_marker_size_width )             ? esc_attr( $portuna_marker_size_width[ 'size' ] )      : '',
    'iconHeight'      => isset( $portuna_marker_size_height )            ? esc_attr( $portuna_marker_size_height[ 'size' ] )     : '',
    'mapStreetView'   => isset( $portuna_map_street_view )              && $portuna_map_street_view                              ? 'true' : 'false',
    'mapTypeControl'  => isset( $portuna_map_types )                    && $portuna_map_types                                    ? 'true' : 'false',
    'mapZoomControl'  => isset( $portuna_map_zoom_control )             && $portuna_map_zoom_control                             ? 'true' : 'false',
    'mapFullScreen'   => isset( $portuna_map_fullscreen_control )       && $portuna_map_fullscreen_control                       ? 'true' : 'false',
    'mapScrollWheel'  => isset( $portuna_map_scrollwheel_control )      && $portuna_map_scrollwheel_control                      ? 'true' : 'false',
    'multipleMarkers' => $portuna_marker_items_multiple,
    'mapStyleTheme'   => $this->set_map_style_theme( $args ),
];

$map_data   = Utils::get_map_data( $map_params, '' );

?>

<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> data-map="<?php echo esc_attr( $map_data ); ?>">
    <?php if ( $hasApiKey ) { ?>
        <div class="portuna-addon--google-map__container"></div>
    <?php } else { ?>
        <div class="portuna-addon--google-map__notice">
            <p><?php echo esc_html__( 'Whoops!', 'portuna-addon' ); ?></p>
        </div>
    <?php } ?>
</div>