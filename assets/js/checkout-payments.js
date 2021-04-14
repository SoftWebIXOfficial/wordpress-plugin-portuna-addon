;
(function ($, paymentInfo, d) {
    'use strict';

    const checkoutPayment = {
        onInit: function() {
            this.getSelectorName();
            this.bindEvents();
        },

        getSelectorName: function() {
            this._elements = {
                $baseWrap: $( '.fondy-payment-system' ),
            }
        },

        bindEvents: function() {
            const {
                    $baseWrap
                } = this._elements,
                {
                    order_status: checkoutStatus,
                    sender_email: userEmail
                } = paymentInfo.callback;

            console.log( paymentInfo.callback );

            if ( checkoutStatus === 'approved' ) {
                if ( $baseWrap[0] ) {
                    let elEmail = $baseWrap.find( '.user-email' );

                    if ( elEmail[0] ) {
                        elEmail.html( ': ' + userEmail );
                    }
                }
            } else {
                // window.location.replace( 'https://softwebixpreview.com/portuna/' );
            }
        }
    };

    $( () => {
        checkoutPayment.onInit();
    } );

} )(jQuery, window.portunaCheckoutPayments, document);