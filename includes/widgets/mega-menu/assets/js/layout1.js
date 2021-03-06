import debounce from 'lodash/debounce';

;
(function ( $, window ) {
    'use strict';

    const megaMenu = {
        onInit: function () {
            const widgetName = {
                'portuna-addon-mega-menu.default' : megaMenu.megaMenuOptions,
            }

            $.each( widgetName, ( widget, callback ) => {
                window.elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
            } );
        },
        megaMenuOptions: function ( $scope ) {
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

    $( window ).on( 'elementor/frontend/init', megaMenu.onInit );
} )( jQuery, window );