;
(function ( $, w ) {
    'use strict';

    const elementorAreaRender = {
        onInit: function() {
            this.bindEvents();
        },
        bindEvents: function() {
            this.eventCloseWindow();
            this.eventElementorManager();
        },
        eventCloseWindow: function () {
            $( '.eicon-close' ).on( 'click', () => {
                $( '.portuna-addon-modal' ).hide();
            } );
        },
        eventElementorManager: function () {
            const elementorControls = elementor.modules.controls.BaseData.extend(
                {
                    ui: function () {
                        let ui = elementor.modules.controls.BaseData.prototype.ui.apply( this, arguments );
                        
                        ui.inputs = '[type="text"]';
                        
                        return ui;
                    },
                    
                    events: function () {
                        return _.extend( elementor.modules.controls.BaseData.prototype.events.apply( this, arguments ), {
                            "change @ui.inputs": "onBaseInputChange"
                        } );
                    },
                    
                    onBaseInputChange: function ( e ) {
                        clearTimeout( this.correctionTimeout );
                        
                        const target   = e.currentTarget,
                              inputVal = this.getInputValue( target );
                        
                        this.validators.slice(0), 
                        this.elementSettingsModel.validators[ this.model.get( 'name' ) ];
                        this.updateElementModel( inputVal, target );
                    },
                    
                    onDestroy: function () {
                        clearInterval( window.elementorAreaInterval );
                    },
                    
                    onRender: function () {
                        elementor.modules.controls.BaseData.prototype.onRender.apply( this, arguments );
                        
                        const _this = this;
            
                        clearInterval( window.elementorAreaInterval );
                            
                        window.elementorAreaInterval = setInterval( function () {
                            const dataLoad = $( 'body' ).attr( 'data-portuna-widget-load' ),
                                  dataKey  = $( 'body' ).attr( 'data-portuna-key' );
                            
                            if ( 'true' == dataLoad && 1 == _this.isRendered ) {
                                let val,
                                    date = new Date().getTime(),
                                    key  = dataKey.split( '***' );


                                val = ( key = key[0] ) + '***' + date;
                                $( 'body' ).attr( 'data-portuna-widget-load', 'false' );

                                _this.setValue(val);
                            }
                        }, 50 );
                    }
                }
            );

            elementor.addControlView( 'elementor_area', elementorControls );
        }
    }

    $( w ).on( 'elementor:init', () => { elementorAreaRender.onInit(); } );

} )( jQuery, window );