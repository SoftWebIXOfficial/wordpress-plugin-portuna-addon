/*
    MAIN VENDORS SCRIPTS.

    --------------------------------------------------------------
    TABLE OF CONTENTS:
        1. VARS.
        1. SWIPER.
    --------------------------------------------------------------
 */
import debounce from 'lodash/debounce';

;
(function ( $, wind ) {
    'use strict';

    /* ===================== */
    /* VARS */
    /* ===================== */
    const swiperClass = document.getElementsByClassName( 'swiper-container' );
    const swipers     = [];

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
        const elArrows  = $(that).parent(),
              arrowPrev = findClosestDescendant( elArrows, '.swiper-button-prev', '.swiper' ),
              arrowNext = findClosestDescendant( elArrows, '.swiper-button-next', '.swiper' );

        // PAGINATION
        const elPagination = that.parentNode.querySelector( ':not(.swiper-slide) .swiper-pagination' );

        if ( elPagination ) {
            elPagination.classList.add( 'swiper-pagination-' + index );
        }

        // GET PARAMS
        const dataLoop           = !!that.getAttribute( 'data-loop' ),
              dataInteraction    = !!that.getAttribute( 'data-interaction' ),
              dataSpeed          = +that.getAttribute( 'data-speed' ),
              dataEffect         = that.getAttribute( 'data-effect' ),
              dataProgressMove   = that.getAttribute( 'data-progress-move' ),
              dataCenterSlide    = !!that.getAttribute( 'data-center-slide' ),
              dataInitialSlide   = +that.getAttribute( 'data-initial-slide' ),
              dataAutoplayDelay  = +that.getAttribute( 'data-autoplay-delay' ),
              dataSimulateTouch  = !!that.getAttribute( 'data-simulate-touch' ),
              dataDirection      = that.getAttribute( 'data-direction' ),
              dataSlidePreview   = +that.getAttribute( 'data-slides-preview' ),
              dataPaginationType = that.getAttribute( 'data-pagination-type' ),
              dataSlideToClicked = !!that.getAttribute( 'data-slide-clicked' ),
              dataSpaceBetween   = +that.getAttribute( 'data-slides-space' ),
              dataAutoHeight     = !!that.getAttribute( 'data-auto-height' );

        let   dataLazy           = +that.getAttribute( 'data-lazy' ),
              dataMousewheel     = +that.getAttribute( 'data-mousewheel' );

        let   dataAutoplay       = !!that.getAttribute( 'data-autoplay' );

        if ( dataAutoplay ) {
            dataAutoplay = {
                delay: dataAutoplayDelay,
                disableOnInteraction: dataInteraction
            }
        }

        if ( dataMousewheel ) {
            dataMousewheel = {
                invert: false
            }
        }

        if ( dataLazy ) {
            dataLazy = {
                loadPrevNext:          true,
                loadPrevNextAmount:    dataLazy,
                loadOnTransitionStart: true,
            }
        }
        // Responsive.
        const dataSlidePreview_xs = that.getAttribute( 'data-slides-preview-xs' ),
              dataSpaceBetween_xs = that.getAttribute( 'data-space-xs' );

        const breakpoints = {
            [ 0 ] : {
                slidesPerView: dataSlidePreview_xs,
                spaceBetween:  dataSpaceBetween_xs,
            },
        };

        // slidesPerColumn:       dataSlideColumn,
        // loopAdditionalSlides:  4,
        // roundLengths:          true,
        // noSwiping:             true,
        // noSwipingClass:        'swiper-no-swiping',
        // watchSlidesVisibility: true,
        // slideVisibleClass:     'swiper-slide-visible',
        // breakpoints,
        // coverflowEffect:       {
        // rotate: 30,
        //     slideShadows: false,
        // },
        // pagination:            {
        //     el:           `.swiper-pagination-${ index }`,
        //         type:         dataPaginationType,
        //         clickable:    true,
        //         renderBullet: ( index, className ) => {
        //         if ( that.querySelector( '.swiper-pagination--numeric' ) ) {
        //             return '<span class="' + className + '">' + ( ( index < 9 ) ? '0' : '' ) + ( index + 1 ) + '</span>';
        //         }
        //
        //         return '<span class="' + className + '"></span>';
        //     }
        // },
        // on:                   {
        //     init: function() {
        //
        //     },
        //     slideChangeTransitionStart: function() {
        //
        //     },
        //     slideChange: function() {
        //
        //     },
        //     lazyImageReady: function() {
        //
        //     },
        // }

        // INIT
        swipers[ index ] = new Swiper( '.' + index, {
            autoplay:              dataAutoplay,
            loop:                  dataLoop,
            simulateTouch:         dataSimulateTouch,
            effect:                dataEffect,
            speed:                 dataSpeed,
            direction:             dataDirection,
            centeredSlides:        dataCenterSlide,
            slidesPerView:         dataSlidePreview,
            spaceBetween:          dataSpaceBetween,
            loopAdditionalSlides:  4,
            roundLengths:          true,
            noSwiping:             true,
            noSwipingClass:        'swiper-no-swiping',
            watchSlidesVisibility: true,
            slideVisibleClass:     'swiper-slide-visible',
            pagination:            {
                el:                `.swiper-pagination-${ index }`,
                type:              dataPaginationType,
                clickable:         true,
            },
            navigation:            {
                nextEl:            arrowNext,
                prevEl:            arrowPrev,
            },
            on: {
                init: function () {
                    const wrapper = $( '.swiper-slide' ).not( '.swiper-slide-active' );

                    setTimeout( () => {
                        wrapper.find( '.animated' ).each( function ( index, elem ) {
                            let settings = $( elem ).data( 'settings' );

                            if ( ! settings ) {
                                return;
                            }

                            if ( ! settings._animation && ! settings.animation ) {
                                return;
                            }

                            let anim = settings._animation || settings.animation;

                            $( elem ).removeClass( 'animated ' + anim ).addClass( 'elementor-invisible' );
                        } );
                    }, 1000 );
                },
                transitionStart: function() {
                    // if ( dataLoop ) {
                        let $wrapperEl = that.swiper.$wrapperEl;
                        let params     = that.swiper.params;
                        $wrapperEl.children(('.' + (params.slideClass) + '.' + (params.slideDuplicateClass)))
                            .each (function () {
                                let idx = this.getAttribute('data-swiper-slide-index');
                                this.innerHTML = $wrapperEl.children('.' + params.slideClass + '[data-swiper-slide-index="' + idx + '"]:not(.' + params.slideDuplicateClass + ')').html();
                            } );
                    // }

                    $( '.swiper-wrapper' ).find( '.swiper-slide-active .elementor-invisible' ).each( function( index, elem ) {
                        let settings = $( elem ).data( 'settings' );

                        if ( ! settings ) {
                            return;
                        }

                        if ( ! settings._animation && ! settings.animation ) {
                            return;
                        }

                        let delay = settings._animation_delay ? settings._animation_delay : 0,
                            anim  = settings._animation || settings.animation;

                        setTimeout( () => {
                            $( elem ).removeClass( 'elementor-invisible' ).addClass( anim + ' animated');
                        }, delay );
                    } );
                },
                transitionEnd: function () {
                    // if ( dataLoop ) {
                        that.swiper.slideToLoop(that.swiper.realIndex, 500, false);
                    // }

                    const wrapper = $( '.swiper-slide' ).not( '.swiper-slide-active' );

                    wrapper.find( '.animated' ).each( function ( index, elem ) {
                        let settings = $( elem ).data( 'settings' );

                        if ( ! settings ) {
                            return;
                        }

                        if ( ! settings._animation && ! settings.animation ) {
                            return;
                        }

                        let anim = settings._animation || settings.animation;

                        $( elem ).removeClass( 'animated ' + anim ).addClass( 'elementor-invisible' );
                    } );
                },
            }
        } );

        $(that).hover(function() {
            //console.log( 'hover' );
            //$(this).swiper.autoplay.stop();
        }, function() {
            //$(this).swiper.autoplay.start();
        });
    };

    window.initSwiper = target => {
        if ( target ) {
            Array.prototype.forEach.call( target, swiper );
        }
    }

    $( window ).on( 'load', () => {
        setTimeout( function () {
            $( window ).trigger( 'resize' );
        }, 200 );

        if ( swiperClass.length ) {
            initSwiper( swiperClass );
        }
    } );

    // $( window ).on( 'resize orientationchange', debounce( () => {
    //     if ( swiperClass.length ) {
    //         initSwiper( swiperClass );
    //     }
    // }, 100 ) );

    $( window ).on( 'elementor/frontend/init', ( ) => {
        if ( ( window.location.href.indexOf( 'elementor-preview' ) > -1 )  ) {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', $scope => {
                if ( swiperClass.length ) {
                    initSwiper( swiperClass );
                }
            });
        }
    } );

} )( jQuery, window );