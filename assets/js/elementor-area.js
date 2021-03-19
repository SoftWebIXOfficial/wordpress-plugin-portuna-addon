;
(function ( $, elm ) {
    'use strict';

    if ( void 0 === window.parent ) return;
    let windowParent = window.parent;

    const elementorAreaBuild = {
        onInit: function () {
            this.bindEvents();
        },
        bindEvents: function () {
            this.eventElementorEdit();
        },
        eventElementorEdit: function () {
            const adminUrl      = window.portuna.resturl,
                  elementorEdit = elm.config.environmentMode.edit,
                  wParent       = void 0 !== windowParent.jQuery;

            elementorEdit &&
                windowParent.jQuery( '#elementor-editor-dark-mode-css' ).length > 0 && windowParent.jQuery( 'body' ).addClass( 'elementor-editor-dark-mode' );

                elm.hooks.addAction( 'frontend/element_ready/global', $scope => {
                    $scope.find( '.widgetarea_warper_edit' ).on( 'click', function () {
                        console.log( 'Click edit.' );
                        const modalFrame      = windowParent.jQuery( '.portuna-addon-modal' ),
                              iFrame          = modalFrame.find( '#widgetarea-control-iframe' ),
                              lightBoxLoad    = modalFrame.find( '.dialog-lightbox-loading' ),
                              lightBox        = modalFrame.find( '.dialog-type-lightbox' ),
                              key             = $(this).parent().attr( 'data-portuna-key' ),
                              id              = $(this).parent().attr( 'data-portuna-id' ),
                              getElementorSrc = adminUrl + 'widgets-content/content_editor/editor/' + key + '-' + id;

                        windowParent
                            .jQuery( 'body' ).attr( 'data-portuna-key', key ),
                            windowParent.jQuery( 'body' ).attr( 'data-portuna-widget-load', 'false' ),
                            lightBox.show(),
                            modalFrame.show(),
                            lightBoxLoad.show(),
                            iFrame.contents().find( '#elementor-loading' ).show(),
                            iFrame.attr( 'src', getElementorSrc );

                            iFrame.on( 'load', () => {
                                lightBoxLoad.hide(),
                                iFrame.show();
                            } );

                        wParent &&
                            windowParent
                                .jQuery( '.portuna-addon-modal' )
                                .find( '.eicon-close' )
                                .on( 'click', () => {
                                    windowParent.jQuery( 'body' ).attr( 'data-portuna-widget-load', 'true' );
                                } );
                    } );
                } );
        }
    };

    $( window ).on( 'elementor/frontend/init', () => { elementorAreaBuild.onInit(); } );
} )( jQuery, window.elementorFrontend );