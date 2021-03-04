import debounce from 'lodash/debounce';

;
(function ( $, window ) {
    'use strict';

    const megaMenuHorizontal = {
        onInit: function () {
            const widgetName = {
                'portuna-addon-mega-menu-horizontal.default' : megaMenuHorizontal.megaMenuHorizontalOptions,
            }

            $.each( widgetName, ( widget, callback ) => {
                window.elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
            } );
        },
        megaMenuHorizontalOptions: function ( $scope ) {
            const wrap      = $( $scope[0] ),
                  megaMenu  = wrap.find( '.portuna-addon-sub-mega-menu' );

            function controlMegaMenuWidth() {
                const absWidth = $( window ).outerWidth( true );

                if ( isHamburger ) {
                    return;
                }


            }

            controlMegaMenuWidth();
            $( window ).on( 'resize orientationchange', debounce( controlMegaMenuWidth, 100 ) );
        }
    }

    $( window ).on( 'elementor/frontend/init', megaMenuHorizontal.onInit );
} )( jQuery, window );