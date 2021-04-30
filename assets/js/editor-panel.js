import data from './pro-widgets.js';

const { __ } = wp.i18n;

;
(function ( $, window, doc ) {

    'use strict';

    const editorPanelSettings = {

        onInit: () => {

            editorPanelSettings.bindEvents();
        },
        bindEvents: () => {

            window.elementor.hooks.addAction( 'panel/open_editor/widget', panel => {

                editorPanelSettings.elementorControls( panel );

                $( document ).on( 'click', '.elementor-component-tab.elementor-tab-control-content', () => {
                    editorPanelSettings.elementorControls( panel );
                } );
            } );
        },
        elementorControls: panel => {

            const el          = panel.$el,
                  dataWidgets = typeof data != 'undefined' ? JSON.parse( JSON.stringify( data.pro_controls_section ) ) : {},
                  dialog      = editorPanelSettings.createDialogMessage(),
                  observer    = new MutationObserver( mutations => {

                      for ( let mutation of mutations ) {

                          if ( mutation.type === 'childList' ) {

                              if ( mutation.addedNodes.length ) {

                                  editorPanelSettings.checkControls( el, dataWidgets );
                                  editorPanelSettings.dialogMessageControl( el, dialog );
                              }
                          }
                      }
                  } ),
                  config      = { childList: true };

            editorPanelSettings.checkControls( el, dataWidgets );
            editorPanelSettings.dialogMessageControl( el, dialog );
            editorPanelSettings.dialogMessageSettings();

            observer.observe( el.find( '#elementor-controls' )[0], config );
        },
        checkControls: ( el, dataWidgets ) => {

            $.each( dataWidgets, ( index, item ) => {

                el.find( `.elementor-section-toggle[data-collapse_id='${item}']` ).closest( '.elementor-control' ).addClass( 'portuna-addon-pro-section elementor-open' );
            } );
        },
        createDialogMessage: () => {

            const settings      = editorPanelSettings.dialogMessageSettings(),
                  dialogMessage = elementorCommon.dialogsManager.createWidget( settings.dialogType, settings.dialogOptions );

            dialogMessage.addButton( {
                name: 'close',
                text: __( 'Close', 'portuna-addon' ),
            } );

            dialogMessage.addButton( {
                name:     'purchase_now',
                text:     __( 'Purchase Now', 'portuna-addon' ),
                callback: () => {
                    open( 'https://pay.fondy.eu/s/0J8D2oVKUlLMab', '_blank' ); // Fondy link.
                },
            } );

            return dialogMessage;
        },
        dialogMessageSettings: () => {

            return {
                dialogType: 'lightbox',
                dialogOptions: {
                    className:     'portuna-addon--pro-modal',
                    id:            'elementor-deactivate-feedback-modal',
                    headerMessage: __( 'Get the Pro', 'portuna-addon' ),
                    message:       __( 'In order to use professional features to expand your toolkit and create sites faster and better, you need to purchase an extended version of the plugin.', 'portuna-addon' ),
                    effects: {
                        hide: 'hide',
                        show: 'show',
                    },
                    hide: {
                        onBackgroundClick: false,
                    },
                    position: {
                        my: 'center',
                        at: 'center',
                    }
                },
            };
        },
        dialogMessageControl: ( el, dialog ) => {

            el.find( '.portuna-addon-pro-section' ).on( 'click', () => {

                dialog.show();
            } );
        }
    }

    $( window ).on( 'load', () => {

        editorPanelSettings.onInit();
    } );

} )( jQuery, window, document );