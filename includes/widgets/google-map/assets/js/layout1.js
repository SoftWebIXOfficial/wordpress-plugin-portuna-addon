import isEqual from 'lodash/isEqual';

;
(function ( $, window ) {
    'use strict';

    // const google = window.google;
    //
    // if ( typeof google === 'undefined' ) {
    //     return;
    // }

    //let map;

    const googleMap = {
        onInit: function () {
            const widgetName = {
                'portuna-addon-google-map.default' : googleMap.googleMapInit,
            }

            $.each( widgetName, ( widget, callback ) => {
                window.elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
            } );
        },
        googleMapInit: function ( $scope ) {
            const wrap       = $( $scope[0] ),
                  container  = wrap.find( '.portuna-addon--google-map__container' ),
                  zoomLevel  = wrap.find( '.portuna-addon--google-map--layout1' ).data( 'map-zoom-level' ),
                  centerLat  = wrap.find( '.portuna-addon--google-map--layout1' ).data( 'map-centerlat' ),
                  centerLon  = wrap.find( '.portuna-addon--google-map--layout1' ).data( 'map-centerlon' );

            const coordinates = { lat: centerLat, lng: centerLon };

            let mapOptions = {
                zoom:   parseInt( zoomLevel ),
                center: coordinates,
            }

            const map = new google.maps.Map( container[0], mapOptions );

            googleMap.markers( wrap );
        },
        markers: function ( selector ) {
            const markers     = [],
                  geocoder    = new google.maps.Geocoder(),
                  addressType = selector.find( '.portuna-addon--google-map--layout1' ).data( 'map-address-type' );

            if ( ! isEqual( addressType, 'address' ) ) {
                const latlon = new google.maps.LatLng( parseFloat(), parseFloat() );
            } else {
                googleMap.geocoder( selector );
            }
        },
        geocoder: function ( selector ) {
            console.log( options );
        },
        createMarker: function ( selector ) {

        }
    }

    $( window ).on( 'elementor/frontend/init', googleMap.onInit );
} )( jQuery, window );