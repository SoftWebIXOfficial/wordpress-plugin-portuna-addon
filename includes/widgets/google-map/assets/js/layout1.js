import has from 'lodash/has';
import isEqual from 'lodash/isEqual';

;
(function ( $, window ) {
    'use strict';

    const googleMap = {
        onInit: function () {
            if ( ! window.google ) return;

            const widgetName = {
                'portuna-addon-google-map.default' : googleMap.googleMapInit,
            }

            $.each( widgetName, ( widget, callback ) => {
                window.elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
            } );
        },
        googleMapInit: function ( $scope ) {
            const wrap        = $( $scope[0] ),
                  container   = wrap.find( '.portuna-addon--google-map__container' ),
                  dataMap     = wrap.find( '.portuna-addon--google-map--layout1' ).data( 'map' ),
                  { zoomLevel, centerLat, centerLng, mapStyleTheme } = dataMap;

            const coordinates = { lat: parseFloat( centerLat || 23.7808875 ) , lng: parseFloat( centerLng || 90.2792373 ) },
                  mapStyle    = decodeURIComponent( mapStyleTheme );

            let mapOptions    = {
                zoom:   parseInt( zoomLevel ),
                center: coordinates,
                styles: mapStyle !== '' ? $.parseJSON( mapStyle ) : {},
            }

            const map = new google.maps.Map( container[0], mapOptions );

            googleMap.markers( map, dataMap );
        },
        markers: function ( map, options ) {
            const multipleMarkers                                  = [],
                  { addressType, type, multipleMarkers: settings } = options;

            if ( type === 'marker' ) {
                $.each( settings, ( index, options ) => {
                    const { centerLat, centerLng } = options;

                    console.log( options );

                    options.centerLat     = options.portuna_marker_lat_multiple;
                    options.centerLng     = options.portuna_marker_lng_multiple;
                    options.markerContent = options.portuna_marker_content_multiple;
                    options.popupMaxWidth = options.portuna_marker_content_width;
                    options.isPopupOpen   = options.portuna_marker_content_opened_switch;
                    options.isCustomIcon  = options.portuna_marker_icon_switch_multiple;
                    options.iconUrl       = options.portuna_marker_icon_multiple[ 'url' ];
                    options.iconWidth     = options.portuna_marker_size_width_multiple[ 'size' ];
                    options.iconHeight    = options.portuna_marker_size_height_multiple[ 'size' ];

                    delete options.portuna_marker_lng_multiple;
                    delete options.portuna_marker_lng_multiple;
                    delete options.portuna_marker_lat_multiple;
                    delete options.portuna_marker_content_multiple;
                    delete options.portuna_marker_content_width;
                    delete options.portuna_marker_content_opened_switch;
                    delete options.portuna_marker_icon_switch_multiple;
                    delete options.portuna_marker_icon_multiple;
                    delete options.portuna_marker_size_width_multiple;
                    delete options.portuna_marker_size_height_multiple;

                    const latlng = new google.maps.LatLng( parseFloat( options.centerLat ), parseFloat( options.centerLng ) );
                    googleMap.createMarker( map, latlng, options );
                } );
            } else {
                const { centerLat, centerLng } = options;

                if ( ! isEqual( addressType, 'address' ) ) {
                    const latlng = new google.maps.LatLng( parseFloat( centerLat ), parseFloat( centerLng ) );
                    googleMap.createMarker( map, latlng, options );
                } else {
                    googleMap.geocoder( map, options );
                }
            }
        },
        geocoder: function ( map, options ) {
            const { addressName } = options;
            const geocode         = address => {
                return new Promise( ( resolve, reject ) => {
                    const geocoder = new window.google.maps.Geocoder();

                    geocoder.geocode( { address }, ( results, status ) => {
                        if ( isEqual( status, 'OK' ) ) {
                            const { location } = results[ 0 ].geometry;

                            resolve( {
                                latlng: location,
                            } );
                        } else if ( isEqual( status, 'ZERO_RESULTS' ) ) {
                            reject( 'The address could not be found.' );
                        } else {
                            reject( `Geocode was not successful for the following reason: ${ status }` );
                        }
                    } );
                } );
            }

            geocode( addressName )
                .then( ( { latlng } ) => {
                    googleMap.createMarker( map, latlng, options );
                } )
                .catch( alert => {
                    console.log( alert );
                } );
        },
        createMarker: function ( map, latlng, options ) {
            googleMap.focusOnMarker( map, latlng );
            googleMap.attachMessage( new window.google.maps.Marker( {
                map: map,
                position: latlng,
            } ), options, map );
        },
        attachMessage: function ( marker, options, map ) {
            const { markerContent, popupMaxWidth, isPopupOpen, isCustomIcon } = options;

            if ( !!markerContent ) {
                marker.info = new window.google.maps.InfoWindow( {
                    content: googleMap.createElement(
                        'div',
                        { 'class': 'my-component' },
                        decodeURIComponent( markerContent )
                    ).outerHTML,
                    maxWidth: popupMaxWidth,
                } );
            }

            if ( isCustomIcon ) {
                const { iconUrl, iconWidth, iconHeight } = options;

                marker.setIcon( {
                    url: iconUrl,
                    scaledSize: new window.google.maps.Size(
                        iconWidth,
                        iconHeight
                    )
                } );
            }

            if ( isPopupOpen && has( marker, 'info' ) ) {
                setTimeout( () =>
                        marker.info.open( map, marker ),
                    1000
                );
            }

            if ( has( marker, 'info' ) ) {
                marker.addListener( 'click', () =>
                    marker.info.open( map, marker )
                );
            }
        },
        focusOnMarker: function ( map, position ) {
            map.setCenter( new google.maps.LatLng( position.lat(), position.lng() ) );
        },
        setMarkerIcon: function () {

        },
        createElement: ( type, attributes, ...children ) => {
            const el = document.createElement( type );

            for ( let key in attributes ) {
                el.setAttribute( key, attributes[ key ] );
            }

            children.forEach( child => {
                if ( typeof child === 'string' ) {
                    el.appendChild( document.createTextNode( child ) );
                } else {
                    el.appendChild( child );
                }
            } );

            return el;
        }
    }

    $( window ).on( 'elementor/frontend/init', googleMap.onInit );
} )( jQuery, window );