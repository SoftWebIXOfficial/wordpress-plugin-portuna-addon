<form action="" method="POST" id="portuna-addon-form-settings">
    <div class="portuna-addon-admin--content-row">
        <div class="portuna-addon-admin--content-plugin">
            <div class="portuna-addon-admin-panel--settings">
                <h1>Settings</h1>

                <!------------- Google API ----------->
                <div class="google-maps-api-settings">
                    <label><?php echo esc_html__( 'Google Map', 'portuna-addon' ); ?></label>
                    <input placeholder="AIzaSyA-10-OHpfss9XvUDWILmos62MnG_L4MYw" name="user_data[google_api_key]" value="<?php echo ( ! isset( $userData[ 'user_data' ][ 'google_api_key' ] ) ) ? '' : ( $userData[ 'user_data' ][ 'google_api_key' ] ) ?>" />
                </div>

                <button disabled class="portuna-addon-form-save-btn" type="submit"><div></div><?php esc_html_e( 'Save Changes', 'portuna-addon' ); ?></button>
            </div>
        </div>
    </div>
</form>