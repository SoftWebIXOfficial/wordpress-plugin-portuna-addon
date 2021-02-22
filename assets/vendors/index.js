/*
    MAIN VENDORS SCRIPTS.

    --------------------------------------------------------------
    TABLE OF CONTENTS:
        1. VARS.
        1. SWIPER.
        1. DEBOUNCE.
    --------------------------------------------------------------
 */
;
(function ( $, wind ) {
    'use strict';

    /* ===================== */
    /* VARS */
    /* ===================== */
    const swiperClass = document.getElementsByClassName( 'swiper-container' );
    const swipers = [];

    /* ===================== */
    /* DEBOUNCE */
    /* ===================== */
    function debounce( func, wait, immediate ) {
        let timeout;

        return function () {
            const context = this;
            const args = arguments;

            let later = function () {
                timeout = null;
                if ( ! immediate ) func.apply( context, args );
            };

            let callNow = immediate && !timeout;
            clearTimeout( timeout );
            timeout = setTimeout( later, wait );

            if ( callNow ) func.apply( context, args );
        };
    };

    /* ===================== */
    /* SWIPER */
    /* ===================== */
    const findClosestDescendant = ( $item, el, closest ) => {
        return $item.find(el).filter( function () {
            return $(this).closest(closest).is($item);
        } )
    };

    const swiper = ( that, key ) => {
        const index = `swiper-unique-${ key }`;

        that.classList.add( index );
        that.setAttribute( 'id', index );

        // ARROWS
        const elArrows = $( that ).parent(),
              arrowPrev = findClosestDescendant( elArrows, '.swiper-button-prev', '.swiper' ),
              arrowNext = findClosestDescendant( elArrows, '.swiper-button-next', '.swiper' );

        // PAGINATION
        const elPagination = that.parentNode.querySelector( ':not(.swiper-slide)  .swiper-pagination' );

        if ( elPagination ) {
            elPagination.classList.add( 'swiper-pagination-' + index );
        }

        // GET PARAMS
        const dataLoop           = that.getAttribute( 'data-loop' ),
              dataSpeed          = that.getAttribute( 'data-speed' ),
              dataEffect         = that.getAttribute( 'data-effect' ),
              dataProgressMove   = that.getAttribute( 'data-progress-move' ),
              dataCenterSlide    = that.getAttribute( 'data-center-slide' ),
              dataInitialSlide   = that.getAttribute( 'data-initial-slide' ),
              dataAutoplay       = that.getAttribute( 'data-autoplay' ),
              dataAutoplayDelay  = that.getAttribute( 'data-autoplay-delay' ),
              dataSimulateTouch  = that.getAttribute( 'data-simulate-touch' ),
              dataDirection      = that.getAttribute( 'data-direction' ),
              dataSlidePreview   = that.getAttribute( 'data-slides-preview' ),
              dataPaginationType = that.getAttribute( 'data-pagination-type' ),
              dataSlideToClicked = that.getAttribute( 'data-slide-clicked' ),
              dataSpaceBetween   = that.getAttribute( 'data-space' ),
              dataAutoHeight     = that.getAttribute( 'data-auto-height' );

        let   dataLazy           = that.getAttribute( 'data-lazy' ),
              dataMousewheel     = that.getAttribute( 'data-mousewheel' );

        if ( dataMousewheel ) {
            dataMousewheel = {
                invert: false
            }
        }

        if ( dataLazy ) {
            dataLazy = {
                loadPrevNext: true,
                loadPrevNextAmount: dataLazy,
                loadOnTransitionStart: true,
            }
        }

        // Responsive.
        const dataSlidePreview_xs = that.getAttribute( 'data-slides-preview-xs' ),
              dataSpaceBetween_xs = that.getAttribute( 'data-space-xs' );

        const breakpoints = {
            [ 0 ] : {
                slidesPerView: dataSlidePreview_xs,
                spaceBetween: dataSpaceBetween_xs,
            },
        };

        // INIT
        swipers[ index ] = new Swiper( '.' + index, {
            simulateTouch:       dataSimulateTouch,
            loop:                dataLoop,
            lazy:                dataLazy,
            direction:           dataDirection,
            speed:               dataSpeed,
            autoplay:            dataEffect,
            effect:              dataAutoplay,
            centeredSlides:      dataCenterSlide,
            initialSlide:        dataInitialSlide,
            slidesPerView:       dataSlidePreview,
            spaceBetween:        dataSpaceBetween,
            slideToClickedSlide: dataSlideToClicked,
            autoHeight:          dataAutoHeight,
            mousewheel:          dataMousewheel,
            loopAdditionalSlides: 4,
            roundLengths:       true,
            noSwiping:          true,
            noSwipingClass: 'swiper-no-swiping',
            watchSlidesVisibility: true,
            slideVisibleClass:   'swiper-slide-visible',
            coverflowEffect:     {
                rotate: 30,
                slideShadows: false,
            },
            navigation: {
                nextEl: arrowNext,
                prevEl: arrowPrev,
            },
            pagination: {
                el:           `.swiper-pagination-${ index }`,
                type:         dataPaginationType,
                clickable:    true,
                renderBullet: ( index, className ) => {
                    if ( that.querySelector( '.swiper-pagination--numeric' ) ) {
                        return '<span class="' + className + '">' + ( ( index < 9 ) ? '0' : '' ) + ( index + 1 ) + '</span>';
                    }

                    return '<span class="' + className + '"></span>';
                }
            },
            breakpoints,
            on: {
                init: function() {

                },
                slideChangeTransitionStart: function() {

                },
                slideChange: function() {

                },
                lazyImageReady: function() {

                },
            }
        } );
    };

    window.initSwiper = target => {
        if ( target ) {
            Array.prototype.forEach.call( target, swiper );
        }
    }

    $( window ).on( 'load', () => {
        if ( swiperClass.length ) {
            initSwiper( swiperClass );
        }
    } );

    $( window ).on( 'elementor/frontend/init', ( ) => {
        if ( ( window.location.href.indexOf( 'elementor-preview' ) > -1 )  ) {
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', $scope => {
                if ( swiperClass.length ) {
                    initSwiper( swiperClass );
                }
            });
        }
    } );

} )( jQuery, window );