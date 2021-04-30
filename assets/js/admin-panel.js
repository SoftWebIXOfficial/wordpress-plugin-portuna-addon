;
(function ($, _, d) {
    'use strict';

    const adminPanelSettings = {
        onInit: function() {
            this.getSelectorName();
            this.settingsPage();
            this.proPage();
        },

        getSelectorName: function() {
            this._elements = {
                $baseWrap:          $( '.portuna-addon-admin' ),
                $linkWrap:          $( '#toplevel_page_portuna-addon' ),
                $formSettingsSave:  $( '#portuna-addon-form-settings' ),
                $formProSave:       $( '#portuna-addon-form-pro' ),
                $saveSettingsBtn:   $( '.portuna-addon-form-save-btn' ),
                $saveApiBtn:        $( '.portuna-addon-api-saved-btn' ),
                $hash:              window.location.hash,
            }
        },

        settingsPage: function() {
            const {
                $baseWrap,
                $linkWrap,
                $formSettingsSave,
                $saveSettingsBtn,
                $hash
            } = this._elements;

            let $hashRgx   = $hash.split('/'),
                $wpSubMenu = $linkWrap.find( '.wp-submenu' ),
                $getUrl    = 'admin.php?page=portuna-addon' + $hashRgx[0],
                $tabSelect;

            $formSettingsSave.on( 'submit', e => {
                e.preventDefault();

                $.post( {
                    url: _.ajaxUrl,
                    data: {
                        nonce:  _.nonces.nonce_api,
                        action: _.actions.action_api,
                        data:   $formSettingsSave.serialize()
                    },
                    beforeSend: () => {
                        $saveSettingsBtn.text( '...' );
                    },
                    success: $ => {
                        console.log($);

                        if ( $.success ) {
                            $saveSettingsBtn.attr( 'disabled', !0 );
                            $saveSettingsBtn.text( _.savedChangesText );
                        }
                    }
                } );
            } ),
                $formSettingsSave.on( 'keyup change paste', 'input, select, textarea, :checkbox, :radio', function() {
                    $saveSettingsBtn.attr( 'disabled', !1 ).text( _.saveChangesText );
                } );

            if ( $hashRgx[ $hashRgx.length - 1 ] == 0 ) {
                let $hashRgxLink = $hashRgx[ $hashRgx.length - 2 ],
                    $matches     = $hashRgxLink.match(/[a-zA-Z]+/g);

                $tabSelect = $matches[0];
            } else {
                let $hashRgxLink = $hashRgx[ $hashRgx.length - 1],
                    $matches     = $hashRgxLink.match(/[a-zA-Z]+/g);

                $tabSelect = $matches[0];
            }

            $baseWrap
                .find( '.portuna-addon-admin--content-menu--' + $tabSelect )
                .addClass( 'nav-is-active' )
                .siblings()
                .removeClass( 'nav-is-active' );

            $hash &&
                $('#toplevel_page_portuna-addon .wp-submenu li a[href="' + $getUrl + '"]')
                    .parent('li')
                    .addClass('current')
                    .siblings()
                    .removeClass('current');

                $wpSubMenu
                    .on( 'click', 'a', function(e) {
                        if ( ! e.currentTarget.hash ) return !0;

                        e.preventDefault();

                        window.location.hash = e.currentTarget.hash;

                        let wp_get_hash = window.location.hash.match(/[a-zA-Z]+/g);

                        $baseWrap
                            .find('.portuna-addon-admin--content-menu--' + wp_get_hash[0])
                            .addClass('nav-is-active')
                            .siblings()
                            .removeClass('nav-is-active');

                        $( this )
                            .parent()
                            .addClass('current')
                            .siblings()
                            .removeClass('current');
                    } );
        },
        proPage: function() {
            const {
                $formProSave,
                $saveApiBtn
            } = this._elements;

            $formProSave.on( 'submit', e => {
                e.preventDefault();

                $.post( {
                    url: _.ajaxUrl,
                    data: {
                        nonce:  _.nonces.pro_api,
                        action: _.actions.pro_api,
                        data:   $formProSave.serialize()
                    },
                    beforeSend: () => {
                        $saveApiBtn.text( '...' );
                    },
                    success: data => {

                        if ( data.success === true ) {
                            $saveApiBtn.attr( 'disabled', !0 );
                            $saveApiBtn.addClass( 'success' );
                            $( '.portuna-addon-admin--license-key-input' ).addClass( 'success' );
                            $saveApiBtn.text( _.licenseKeySuccess );
                        } else {
                            $saveApiBtn.attr( 'disabled', !0 );
                            $saveApiBtn.addClass( 'error' );
                            $saveApiBtn.text( _.licenseKeyError );
                        }
                    }
                } );
            } ),
                $formProSave.on( 'keyup change paste', 'input, select, textarea, :checkbox, :radio', function() {
                    $saveApiBtn.attr( 'disabled', !1 );
                    $saveApiBtn.html( _.saveChangesText ).removeClass( 'error' );

                    if ( $(this).val() === '' ) {
                        $saveApiBtn.attr( 'disabled', 1 );
                    }
                } );

        }
    };

    $( () => {
        adminPanelSettings.onInit();
    } );

} )(jQuery, window.portunaAjax, document);