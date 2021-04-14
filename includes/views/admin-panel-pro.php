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
            <p class="portuna-addon-admin--panel-price"><?php esc_html_e( '$12', 'portuna-addon' ); ?></p>
            <p class="portuna-addon-admin--panel-btn">
                <a href="https://pay.fondy.eu/s/0J8D2oVKUlLMab" target="_blank" rel="noopener noreferrer" class="portuna-addon-admin--panel-redirect"><?php esc_html_e( 'Buy Now', 'portuna-addon' ); ?></a>
            </p>
            <ul class="portuna-addon-admin--panel-lists">
                <li><?php esc_html_e( 'Support', 'portuna-addon' ); ?></li>
            </ul>
        </div>
    </div>
</div>