;
(function ($, _, d) {
    'use strict';

    const adminPanelSettings = {
        onInit: function() {
            this.getSelectorName();
            this.bindEvents();
            this.payments();
        },

        getSelectorName: function() {
            this._elements = {
                $baseWrap:      $( '.portuna-addon-admin' ),
                $linkWrap:      $( '#toplevel_page_portuna-addon' ),
                $formSave:      $( '#portuna-addon-form-settings' ),
                $saveBtn:       $( '.portuna-addon-form-save-btn' ),
                $hash:          window.location.hash,
            }
        },

        bindEvents: function() {
            const {
                $baseWrap,
                $linkWrap,
                $formSave,
                $saveBtn,
                $hash
            } = this._elements;

            let $hashRgx   = $hash.split('/'),
                $wpSubMenu = $linkWrap.find( '.wp-submenu' ),
                $getUrl    = 'admin.php?page=portuna-addon' + $hashRgx[0],
                $tabSelect;

            $formSave.on( 'submit', e => {
                e.preventDefault();

                $.post( {
                    url: _.ajaxUrl,
                    data: {
                        nonce:  _.nonce,
                        action: _.action,
                        data:   $formSave.serialize()
                    },
                    beforeSend: () => {
                        $saveBtn.text( '...' );
                    },
                    success: ($) => {
                        console.log($);

                        if ( $.success ) {
                            $saveBtn.attr( 'disabled', !0 );
                            $saveBtn.text( _.savedChangesText );
                        }
                    }
                } );
            } ),
                $formSave.on( 'keyup change paste', 'input, select, textarea, :checkbox, :radio', function() {
                    $saveBtn.attr( 'disabled', !1 ).text( _.saveChangesText );
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

                        $(this)
                            .parent()
                            .addClass('current')
                            .siblings()
                            .removeClass('current');
                    } );
        },
        payments: function() {
            if ( ! window.$ipsp ) return;

            const {
                $baseWrap,
            } = this._elements;
            // $( document ).on( 'ready', () => {
            const purchasedLink = $baseWrap.find( '.portuna-addon-admin--panel-redirect' );

            var button = $ipsp.get('button');

            button.setMerchantId( 1474027 );
            button.setAmount( '10', 'USD', true );
            button.setHost( 'pay.fondy.eu' );
            console.log( window );
            $ipsp('button').addField({
                'label':'Account Id',
                'name' :'account_id',
                'value':'127318273',   //не обязательно, по умолчанию пустое
                'readonly':true, //не обязательно, по умолчанию false
                'required':true, //не обязательно, по умолчанию false
            });

            purchasedLink.attr( 'href', button.getUrl() );


        }
    };

    $( () => {
        adminPanelSettings.onInit();
    } );

} )(jQuery, window.portunaAjax, document);