<?php

\Cloudipsp\Configuration::setMerchantId( 1396424 );
\Cloudipsp\Configuration::setSecretKey( 'test' );

$data = [
    'order_desc' => 'tests SDK',
    'currency' => 'USD',
    'amount' => 1000,
    'response_url' => 'http://site.com/responseurl',
    'server_callback_url' => 'http://site.com/callbackurl',
    'sender_email' => 'test@fondy.eu',
    'lang' => 'ru',
    'product_id' => 'some_product_id',
    'lifetime' => 36000,
    'merchant_data' => array(
        'custom_data1' => 'Some string',
        'custom_data2' => '00000000000',
        'custom_data3' => '3!@#$%^&(()_+?"}'
    )
];

$url = \Cloudipsp\Checkout::url($data);
$data = $url->getData();

//var_dump( home_url() );

?>

<div class="portuna-addon-admin-panel--pro">
    <h2><?php esc_html_e( 'Flexible Pricing for Everyone', 'portuna-addon' ); ?></h2>
    <form action="" method="POST" id="portuna-addon-form-pro">
        <!------------- Set License Key ----------->
        <div class="google-maps-api-settings">
            <label><?php echo esc_html__( 'License Key', 'portuna-addon' ); ?></label>
            <input placeholder="" name="user_data[google_api_key]" value="<?php echo ( ! isset( $userData[ 'user_data' ][ 'google_api_key' ] ) ) ? '' : ( $userData[ 'user_data' ][ 'google_api_key' ] ) ?>" />
        </div>
    </form>
    <div class="portuna-addon-admin-panel--container">
        <div class="portuna-addon-admin--panel">
            <h3 class="portuna-addon-admin--panel-heading"><?php esc_html_e( 'Free', 'portuna-addon' ); ?></h3>
            <p class="portuna-addon-admin--panel-price"><?php esc_html_e( '$0', 'portuna-addon' ); ?></p>
            <ul class="portuna-addon-admin--panel-lists">
                <li><?php esc_html_e( 'Support', 'portuna-addon' ); ?></li>
            </ul>
        </div>
        <div class="portuna-addon-admin--panel">
            <h3 class="portuna-addon-admin--panel-heading"><?php esc_html_e( 'Pro', 'portuna-addon' ); ?></h3>
            <p class="portuna-addon-admin--panel-price"><?php esc_html_e( '$10', 'portuna-addon' ); ?></p>
            <p class="portuna-addon-admin--panel-btn">
                <a href="#" target="_blank" rel="noopener noreferrer" class="portuna-addon-admin--panel-redirect"><?php esc_html_e( 'Buy Now', 'portuna-addon' ); ?></a>
            </p>
            <ul class="portuna-addon-admin--panel-lists">
                <li><?php esc_html_e( 'Support', 'portuna-addon' ); ?></li>
            </ul>
        </div>
    </div>
</div>

<?php
//Payment button
//Payment card verification url scheme B(host-to-host) https://docs.fondy.eu/docs/page/11/
$callback        = file_get_contents( 'php://input' );
$callback_object = simplexml_load_string( $callback );

echo $callback_object;


?>