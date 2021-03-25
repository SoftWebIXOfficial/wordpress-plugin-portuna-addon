<?php

use \PortunaAddon\Helpers\Utils;

extract( $args );

$this->add_render_attribute( 'wrapper', 'class', 'portuna-addon--google-map--layout1' );

$options     = \PortunaAddon\Helpers\Options::instance();
$userData    = $options->get_option( 'user_data', [] );
$hasApiKey   = ! empty( $userData[ 'user_data' ][ 'google_api_key' ] ) && '' != $userData[ 'user_data' ][ 'google_api_key' ];

$map_params  = [
    'type'         => isset( $portuna_google_map_type ) ? esc_attr( $portuna_google_map_type )    : '',
    'zoom-level'   => isset( $portuna_map_zoom )        ? esc_attr( $portuna_map_zoom[ 'size' ] ) : '',
    'address-type' => isset( $portuna_address_type )    ? esc_attr( $portuna_address_type )       : '',
    'address'      => isset( $portuna_map_address )     ? esc_attr( $portuna_map_address )        : '',
    'centerlat'    => isset( $portuna_latitude )        ? esc_attr( $portuna_latitude )           : '',
    'centerlon'    => isset( $portuna_longitude )       ? esc_attr( $portuna_longitude )          : '',
];

$map_data   = Utils::get_map_data( $map_params, '' );

?>

<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> data-map="<?php echo esc_attr( $map_data ); ?>">
    <?php if ( $hasApiKey ) { ?>
        <?php print_r( 'Visible Text' ); ?>
        <div class="portuna-addon--google-map__container"></div>
    <?php } else { ?>
        <div class="portuna-addon--google-map__notice">
            <p><?php echo esc_html__( 'Whoops!', 'portuna-addon' ); ?></p>
        </div>
    <?php } ?>
</div>