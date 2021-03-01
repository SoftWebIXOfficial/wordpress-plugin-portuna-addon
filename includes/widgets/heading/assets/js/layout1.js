;(function ($, w, d) {
    'use strict';

    /****** ******/
    const Handler = function ( $scope, $ ) {
        const $bannerElement = $scope.find('h1');

        $bannerElement.on( 'click', () => {
            //alert('yahooo suka!');
        } );
    };

    $( window ).on( 'elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/portuna_addon_advanced_heading.default', Handler );
    } );
} )(jQuery, window, document);