<?php
    $options     = \PortunaAddon\Helpers\Options::instance();
    $validation  = $options->get_option( 'validation' );
    $userLicense = $options->get_option( 'user_license' );

    $license_key = $validation !== 'valid' ? $userLicense[ 'user_license' ][ 'license_key' ] : str_repeat( '&#8226;', 20 ) . substr( $userLicense[ 'user_license' ][ 'license_key' ], -10 );
?>
<div class="portuna-addon-admin-panel--pro">
    <!------------- <h2><?php esc_html_e( 'Flexible Pricing for Everyone', 'portuna-addon' ); ?></h2> ----------->
    <form action="" method="POST" id="portuna-addon-form-pro">
        <!------------- Set License Key ----------->
        <div class="portuna-addon-admin--license">
            <div class="portuna-addon-admin--license-key">
                <div class="portuna-addon-admin--license-key-container">
                    <input class="portuna-addon-admin--license-key-input <?php echo $validation !== 'valid' ? null : esc_attr( 'success' ); ?>" autocomplete="off" placeholder="<?php echo esc_html__( 'Put your license key here...', 'portuna-addon' ); ?>" name="user_license[license_key]" <?php echo $validation !== 'valid' ? null : 'disabled' ?> value="<?php echo ( ! isset( $userLicense[ 'user_license' ][ 'license_key' ] ) ) ? '' : $license_key ?>" />
                    <?php if ( $validation !== 'valid' ) { ?>
                        <button disabled class="portuna-addon-admin--license-key-btn portuna-addon-api-saved-btn" type="submit"><div></div><?php esc_html_e( 'Save Key', 'portuna-addon' ); ?></button>
                    <?php } else { ?>
                        <button disabled class="portuna-addon-admin--license-key-btn portuna-addon-api-disabled-btn success" type="submit"><div></div><?php esc_html_e( 'Valid', 'portuna-addon' ); ?></button>
                    <?php } ?>
                </div>
            </div>
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
            <?php if ( $validation !== 'valid' ) { ?>
                <p class="portuna-addon-admin--panel-price"><?php esc_html_e( '$12', 'portuna-addon' ); ?></p>
            <?php } else { ?>
                <p class="portuna-addon-admin--panel-purchased"><?php esc_html_e( 'Purchased', 'portuna-addon' ); ?></p>
            <?php } ?>
            <?php if ( $validation !== 'valid' ) { ?>
                <p class="portuna-addon-admin--panel-btn">
                    <a href="https://pay.fondy.eu/s/0J8D2oVKUlLMab" target="_blank" rel="noopener noreferrer" class="portuna-addon-admin--panel-redirect"><?php esc_html_e( 'Buy Now', 'portuna-addon' ); ?></a>
                </p>
            <?php } ?>
            <ul class="portuna-addon-admin--panel-lists">
                <li><?php esc_html_e( 'Support', 'portuna-addon' ); ?></li>
            </ul>

        </div>
    </div>
</div>