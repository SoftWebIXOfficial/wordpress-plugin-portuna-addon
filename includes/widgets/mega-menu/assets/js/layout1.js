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
            const wrap           = $( $scope[0] ),
                  megaMenu       = wrap.find( 'ul.portuna-addon-sub-mega-menu' ),
                  subMenu        = wrap.find( 'ul.portuna-addon-sub-menu' ),
                  subMenuInverse = wrap.find( 'ul.portuna-addon-sub-menu.inverse-side' );

            let   clearSubMenuPosition = false,
                  clearMegaMenuWidth   = false;

            function controlSubMenuPosition() {
                const absWidth = $( 'body' ).outerWidth( true );

                if ( clearSubMenuPosition ) {
                    subMenuInverse.removeClass( 'inverse-side' );

                    clearSubMenuPosition = false;
                }

                if ( subMenu[0] ) {
                    subMenu.each( function() {
                        const that                  = $( this ),
                              subMenuOffsetLeft     = that.offset().left,
                              subMenuOffsetRight    = subMenuOffsetLeft + that.outerWidth( true ),
                              subMenuSidePosition   = that.closest( '.portuna-addon--mega-menu--layout1' ).hasClass( 'portuna-addon--mega-menu--layout1-left-side' ) ? 'left-side' : 'right-side';

                        if ( 'right-side' === subMenuSidePosition ) {
                            if ( subMenuOffsetRight >= absWidth ) {
                                that.addClass( 'inverse-side' );

                                clearSubMenuPosition = true;
                            } else if ( subMenuOffsetLeft < 0 ) {
                                that.removeClass( 'inverse-side' );
                            }
                        } else {
                            if ( subMenuOffsetLeft < 0 ) {
                                that.addClass( 'inverse-side' );

                                clearSubMenuPosition = true;
                            } else if ( subMenuOffsetRight >= absWidth ) {
                                that.removeClass( 'inverse-side' );
                            }
                        }
                    } );
                }
            }
            controlSubMenuPosition();

            $( window ).on( 'resize orientationchange', debounce( controlSubMenuPosition, 100 ) );

            function controlMegaMenuWidth() {
                const absWidth = $( 'body' ).outerWidth( true );

                if ( clearMegaMenuWidth ) {
                    megaMenu.css( {
                        'maxWidth': ''
                    } );

                    clearMegaMenuWidth = false;
                }

                // if ( isHamburger ) {
                //     return;
                // }

                if ( megaMenu[0] ) {
                    megaMenu.each( function() {
                        const that                  = $( this ),
                              megaMenuX             = that.css( 'transform' ).replace( /,/g, '' ).split( ' ' )[4] || 0,
                              megaMenuOffsetLeft    = that.offset().left - megaMenuX,
                              megaMenuOffsetRight   = megaMenuOffsetLeft + that.outerWidth( true ),
                              megaMenuSidePosition  = that.closest( '.portuna-addon--mega-menu--layout1' ).hasClass( 'portuna-addon--mega-menu--layout1-left-side' ) ? 'left-side' : 'right-side';

                        if ( 'right-side' === megaMenuSidePosition ) {
                            if ( megaMenuOffsetRight >= absWidth ) {
                                that.css( {
                                    'maxWidth' : absWidth - megaMenuOffsetLeft - 5
                                } );

                                clearMegaMenuWidth = true;
                            }
                        } else {
                            if ( megaMenuOffsetLeft < 0 ) {
                                that.css( {
                                    'maxWidth' : megaMenuOffsetRight - 5
                                } );

                                clearMegaMenuWidth = true;
                            }
                        }
                    } );
                }
            }
            controlMegaMenuWidth();

            $( window ).on( 'resize orientationchange', debounce( controlMegaMenuWidth, 100 ) );
        }
    }

    $( window ).on( 'elementor/frontend/init', megaMenu.onInit );
} )( jQuery, window );