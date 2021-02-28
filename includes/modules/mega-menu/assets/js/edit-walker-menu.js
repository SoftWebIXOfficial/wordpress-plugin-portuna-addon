;
(function ( $, wind ) {
    'use strict';

    window.navMenuSettings = {
        onInit: function () {
            this.bindEvents();
        },
        bindEvents: function () {
            this.initTriggers();
            this.initNavSettings();
            this.uploadCustomImage();
            this.editMegaMenu();
        },
        initTriggers: function () {
            var self = this;

            $( '#menu-to-edit li' ).addClass( 'portuna-addon-mega-menu' );

            const mutationObserver = new MutationObserver( mutations => {
                $.each( mutations, ( index, mutation ) => {
                    if ( mutation.type == 'childList' ) {
                        if ( mutation.addedNodes.length ) {
                            const item = mutation.addedNodes[ 0 ];
                            $(item).addClass('portuna-addon-mega-menu');
                            self.initNavSettings();
                        }
                    }
                } )
            } );

            mutationObserver.observe( $('#menu-to-edit')[0], {
                childList: true,
            } );
        },
        initNavSettings: function () {
            if ( ! $( '.portuna-addon-mega-menu' )[0] ) {
                return;
            }

            const elSelectTypeIcon     = $( '.portuna-addon-icon-type' );
            const elIconColor          = $( '.portuna-addon-color' );
            const elCheckbox           = $( '.portuna-addon-mega-menu-checkbox' );

            tail.select( '.portuna-addon-icon-type' );

            // Disable-Enable edit button when checkbox is unchecked or checked.
            elSelectTypeIcon.on( 'change', function() {
                const valSelected = $(this).val();

                if ( valSelected === 'icon_none' ) {
                    $(this).closest( 'li.portuna-addon-mega-menu' ).find('.field-is-icon-lib').hide();
                    $(this).closest( 'li.portuna-addon-mega-menu' ).find('.field-is-icon-cus').hide();
                    $(this).closest( 'li.portuna-addon-mega-menu' ).find('.field-is-icon-color').hide();
                } else if ( valSelected === 'icon_lib' ) {
                    $(this).closest( 'li.portuna-addon-mega-menu' ).find('.field-is-icon-cus').hide();
                    $(this).closest( 'li.portuna-addon-mega-menu' ).find('.field-is-icon-lib').show();
                    $(this).closest( 'li.portuna-addon-mega-menu' ).find('.field-is-icon-color').show();
                } else if ( valSelected === 'icon_custom' ) {
                    $(this).closest( 'li.portuna-addon-mega-menu' ).find('.field-is-icon-lib').hide();
                    $(this).closest( 'li.portuna-addon-mega-menu' ).find('.field-is-icon-cus').show();
                    $(this).closest( 'li.portuna-addon-mega-menu' ).find('.field-is-icon-color').show();
                }

            } ).trigger( 'change' );

            tail.select( '.portuna-addon-select-icon', {
                search: true,
                placeholder: 'Select your icon...',
                cbComplete: item => {
                    const labels = $( item ).find( '.select-label' );

                    if ( ! labels ) {
                        return;
                    }

                    var getList = $( '.dropdown-optgroup > .dropdown-option' ).length;

                    for ( let i = 0; i <= getList; i++ ) {
                        let getCurrent = $( `.dropdown-option:eq(${i})` );

                        if ( labels.text() === getCurrent.find( '.text-name' ).text() ) {
                            const getKey  = getCurrent.data( 'key' ),
                                  getName = getCurrent.find( '.text-name' ).text();

                            labels.html(`<span class="label-inner"><i class="${getKey}" style="margin-right: 3px;"></i>${getName}</span>`);
                        }
                    }
                },
                cbLoopItem: (item, group, search) => {
                    var li = document.createElement("LI");

                    li.className = "dropdown-option" + (item.selected? " selected": "") + (item.disabled? " disabled": "");

                    if ( search && search.length > 0 && this.con.searchMarked ) {
                        var name = item.value.replace(new RegExp("(" + search + ")", "i"), "<mark>$1</mark>"),
                            icon = item.key;
                    } else {
                        var name = item.value,
                            icon = item.key;
                    }

                    li.innerHTML =  "<div class='item-inner'>"
                                 + "    <div class='item-text'>"
                                 + "        <i class='" + icon + "'></i>"
                                 + "        <span class='text-name'>" + name + "</span>"
                                 + "    </i>"
                                 + "</div>"
                    return li;
                },
            } );

            $( '.portuna-addon-mega-menu' ).each(function() {
                let currentIcon    = $(this).find( '.portuna-addon-select-icon' );
                let currentClosest = $(this).closest( 'li.portuna-addon-mega-menu' );

                tail.select( currentIcon[0] ).on('change', function (item) {
                    const labels = currentClosest.find( '.field-is-icon-lib .select-label' );

                    const getKey   = item.key,
                          getName  = item.value;

                    labels.html(`<span class="label-inner"><i class="${getKey}" style="margin-right: 3px;"></i>${getName}</span>`);
                } );
            } );

            elIconColor.wpColorPicker();

            // Disable-Enable edit button when checkbox is unchecked or checked.
            $( elCheckbox ).on( 'change', function() {
                $(this).closest( 'li.portuna-addon-mega-menu' ).find('.portuna-addon-mega-menu-edit-btn').prop( 'disabled', ! this.checked );
            } ).trigger( 'change' );
        },
        uploadCustomImage: function () {
            const uploadCustomIcon = function( item_id, thumb_id ) {
                wp.media.post( 'portuna-addon-menu-set-thumbnail', {
                    json:         true,
                    post_id:      item_id,
                    thumbnail_id: thumb_id,
                    _wpnonce:     customIcon.settings.nonce
                } ).done( html => {
                    $( `#field-is-icon-cus-${ item_id }` ).html( html );
                } );
            }

            $( '#menu-to-edit' )
                .on( 'click', '.menu-item .portuna-addon-set-custom-icon', function( e ) {
                    e.preventDefault();
                    e.stopPropagation();

                    const item_id = $( this ).data( 'id' );

                    let uploader = wp.media( {
                        multiple: false
                    } ).on( 'select', () => {
                        const attachment = uploader.state().get( 'selection' ).first().toJSON();
                        uploadCustomIcon( item_id, attachment.id );
                    } ).open();
                } ).on( 'click', '.menu-item .portuna-addon-reset-custom-icon', function( e ) {
                    e.preventDefault();
                    e.stopPropagation();

                    const item_id = $( this ).parent().find( '.portuna-addon-icon-src' ).val();
                    uploadCustomIcon( item_id, -1 );
                } );
        },
        editMegaMenu: function () {
            // Open editor mega menu.
            $( '#menu-to-edit' )
                .on( 'click', '.menu-item .portuna-addon-mega-menu-edit-btn', function() {
                    $( 'body, html' ).css( 'overflow', 'hidden' );
                    $( '.portuna-mega-menu-wrapper' ).show();
                    const adminUrl = customIcon.rest_url,
                          key_id   = $( '#menu' ).val(),
                          item_id  = $( this ).data( 'item-id' ),
                          url      = adminUrl + 'widgets-content/content_editor/widget/' + item_id + '-' + key_id;

                    $( '<iframe>', {
                        src: url,
                        id:  'portuna-mega-menu-iframe',
                        frameborder: 0,
                    } ).appendTo( '.portuna-mega-menu-wrapper .portuna-mega-menu-popup' );

                    $( '#portuna-mega-menu-iframe' ).on( 'load', () => {
                        $( '.portuna-mega-menu-popup-close' ).show();
                    } );
                } );

            // Close editor mega menu.
            $( '.portuna-mega-menu-popup-close' )
                .on( 'click', function() {
                    $( this ).hide();
                    $( '.portuna-mega-menu-wrapper' ).hide();
                    $( '.portuna-mega-menu-popup' ).html('');
                } );
        }
    }

    window.navMenuSettings.onInit();
} )( jQuery, window );