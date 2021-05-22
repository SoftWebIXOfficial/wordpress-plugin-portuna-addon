;
(function ( $, window ) {

    'use strict';

    const megaMenuEditor = {
        activeSection:  false,
        currentElement: false,
        currentSection: false,
        prevSection:    false,

        onInit:         () => {
            elementor.channels.editor.on( 'section:activated', megaMenuEditor.sectionActivated );
        },

        sectionActivated: ( sectionName, editor ) => {
            let desktopSections = [
                    //'section_general',
                    'section_menu_container_style',
                    'section_main_menu_style',
                    'section_sub_menu_style',
                    'section_icon_style',
                    'section_badge_style',
                    'section_arrow_style',
                ],
                mobileSections = [
                    'section_mobile_layout',
                    'section_mobile_menu_toggle_style',
                    'section_mobile_menu_container_style',
                    'section_mobile_menu_items_style',
                    'section_mobile_menu_advanced_style'
                ];
        }
    }

    const megaMenuExtensions = function( $scope ) {


    }

    // $( window ).on( 'elementor:init', megaMenuEditor.onInit );
    $( window ).on( 'elementor:init', () => { console.log( 'init' ) } ); 

} )( jQuery, window );