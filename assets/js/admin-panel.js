;
(function ($, _, d) {
    'use strict';

    const adminPanelSettings = {
        onInit: function() {
            this.getSelectorName();
            this.bindEvents();
        },

        getSelectorName: function() {
            this._elements = {
                $formSave:      $( '#portuna-addon-form' ),
                $saveBtn:       $( '.portuna-addon-form-save-btn' ),
            }
        },

        bindEvents: function() {
            const {
                $formSave,
                $saveBtn
            } = this._elements;
            
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
                        $saveBtn.text( '.....' );
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
        },
    };

    $( () => {
        adminPanelSettings.onInit();
    } );

} )(jQuery, window.portunaAjax, document);