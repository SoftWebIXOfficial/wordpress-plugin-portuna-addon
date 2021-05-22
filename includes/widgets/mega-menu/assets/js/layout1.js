import debounce from 'lodash/debounce';

;
(function ( $, window ) {

    'use strict';

    const megaMenu = {

        onRegister: function () {

            const widgetName = {
                'portuna-addon-mega-menu.default' : megaMenu.onInit,
            }

            if ( window.elementorFrontend && window.elementorFrontend.hooks ) {

                $.each( widgetName, ( widget, callback ) => {

                    window.elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
                } );
            }
        },

        onInit: function ( $scope ) {

            let $target   = $scope,
                instance  = null;

            instance = new megaMenuExtensions( $target );
            instance.init( instance );
        },
    }

    const megaMenuExtensions = function( $scope ) {

        const self           = this,
              el             = $scope,
              wrap           = $( $scope[0] ),
              megaMenu       = wrap.find( 'ul.portuna-addon-sub-mega-menu' ),
              subMenu        = wrap.find( 'ul.portuna-addon-sub-menu' ),
              subMenuInverse = wrap.find( 'ul.portuna-addon-sub-menu.inverse-side' );

        let   clearSubMenuPosition = false,
              clearMegaMenuWidth   = false;

        self.init = function () {

            // Event Loaded.
            //self.subMenuControlWidth();
            self.megaMenuControlBreakpoint();

            // Event Resize.
            const resizerLists = {
                'positionResize'     : self.megaMenuControlPosition,
                'widthResize'        : self.megaMenuControlWidth,
                'breakpointResize'   : self.megaMenuControlBreakpoint,
            }

            $.each( resizerLists, ( resizerList, callback ) => {
                $( window ).on( 'resize orientationchange', debounce( callback, 100 ) );
            } );
        };

        self.megaMenuControlPosition       = function () {

            const absWidth = $( 'body' ).outerWidth( true );

            if ( clearSubMenuPosition ) {
                
                subMenuInverse.removeClass( 'inverse-side' );
                clearSubMenuPosition = false;
            }

            if ( subMenu[0] ) {

                subMenu.each( function() {

                    const that                = $( this ),
                          subMenuOffsetLeft   = that.offset().left,
                          subMenuOffsetRight  = subMenuOffsetLeft + that.outerWidth( true ),
                          subMenuSidePosition = that.closest( '.portuna-addon--mega-menu--layout1' ).hasClass( 'portuna-addon--mega-menu--layout1-left-side' ) ? 'left-side' : 'right-side';

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
        };

        self.megaMenuControlWidth          = function () {

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

                    const that                 = $( this ),
                          megaMenuX            = that.css( 'transform' ).replace( /,/g, '' ).split( ' ' )[4] || 0,
                          megaMenuOffsetLeft   = that.offset().left - megaMenuX,
                          megaMenuOffsetRight  = megaMenuOffsetLeft + that.outerWidth( true ),
                          megaMenuSidePosition = that.closest( '.portuna-addon--mega-menu--layout1' ).hasClass( 'portuna-addon--mega-menu--layout1-left-side' ) ? 'left-side' : 'right-side';

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
        };

        self.subMenuControlWidth           = function () {
            const windowWidth = $( window ).width(),
                  subMenus    = $scope.find( '.portuna-addon-menu-item > ul' );

            subMenus.each( function() {
                const menuOffsetLeft = $( this ).closest( '.portuna-addon-menu-item' ).offset().left;

                $( this ).width( windowWidth );
            } );
        };

        self.megaMenuControlBreakpoint     = function () {

            const wrapClass         = $scope.find( '.portuna-addon--mega-menu--layout1' );
            const breakpointEnabled = $scope.find( '.portuna-addon--mega-menu--layout1-breakpoint--yes' );

            if ( breakpointEnabled ) {

                const absWidth     = $( 'body' ).outerWidth( true ),
                      windowWidth  = $( window ).outerWidth( true );
                let   scopeClasses = ( $scope.attr( 'class' ) || '' ).split(' ');

                for ( let i = 0, len = scopeClasses.length; i < len; i++ ) {
                    switch ( scopeClasses[ i ] ) {
                        case 'portuna-addon-menu-breakpoint--mobile':
                            if ( windowWidth < '768' ) {
                                wrapClass.addClass( 'portuna-addon-menu--responsibility' );
                                self.megaMenuControlResponsibility();
                            } else {
                                wrapClass.removeClass( 'portuna-addon-menu--responsibility' );
                            }

                            break;
                        case 'portuna-addon-menu-breakpoint--tablet':
                            if ( windowWidth < '1025' ) {
                                wrapClass.addClass( 'portuna-addon-menu--responsibility' );
                                self.megaMenuControlResponsibility();
                            } else {
                                wrapClass.removeClass( 'portuna-addon-menu--responsibility' );
                            }

                            break;
                        case 'portuna-addon-menu-breakpoint--custom':
                            const scopeObjStyle    = window.getComputedStyle( $scope[0] ),
                                  scopeGetValue    = scopeObjStyle.getPropertyValue( '--breakpoint' ),
                                  customBreakpoint = parseInt( scopeGetValue );

                            if ( windowWidth <= customBreakpoint ) {
                                wrapClass.addClass( 'portuna-addon-menu--responsibility' );
                                self.megaMenuControlResponsibility();
                            } else {
                                wrapClass.removeClass( 'portuna-addon-menu--responsibility' );
                            }

                            break;
                    }
                }
            }
        }

        self.megaMenuControlResponsibility = function () {

            const menuOpen    = $scope.find( '.portuna-addon--mega-menu--toggle.open-menu' ),
                  menuClose   = $scope.find( '.portuna-addon--mega-menu--toggle.close-menu' ),
                  menuContent = $scope.find( '.portuna-addon--mega-menu--content' );

            menuOpen.on( 'click', () => {
                menuContent.addClass( 'show' );
                menuOpen.hide();
            } );

            menuClose.on( 'click', () => {
                menuContent.removeClass( 'show' );
                menuOpen.show();
            } );
        }
    }

    $( window ).on( 'elementor/frontend/init', megaMenu.onRegister );

} )( jQuery, window );