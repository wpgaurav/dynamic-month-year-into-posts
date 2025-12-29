/**
 * Dynamic Month & Year into Posts - Block Editor Integration
 *
 * Adds a toolbar button to insert dynamic date shortcodes.
 */

( function() {
    'use strict';

    // Wait for DOM ready
    wp.domReady( function() {
        var __ = wp.i18n.__;
        var registerFormatType = wp.richText.registerFormatType;
        var unregisterFormatType = wp.richText.unregisterFormatType;
        var insert = wp.richText.insert;
        var create = wp.richText.create;
        var createElement = wp.element.createElement;
        var useState = wp.element.useState;
        var Fragment = wp.element.Fragment;
        var RichTextToolbarButton = wp.blockEditor.RichTextToolbarButton;
        var Popover = wp.components.Popover;
        var Button = wp.components.Button;

        // Shortcode categories
        var shortcodeCategories = [
            {
                label: 'Year',
                shortcodes: [
                    { code: '[year]', desc: 'Current year' },
                    { code: '[nyear]', desc: 'Next year' },
                    { code: '[pyear]', desc: 'Previous year' }
                ]
            },
            {
                label: 'Month',
                shortcodes: [
                    { code: '[month]', desc: 'Current month' },
                    { code: '[mon]', desc: 'Month (short)' },
                    { code: '[nmonth]', desc: 'Next month' },
                    { code: '[pmonth]', desc: 'Previous month' }
                ]
            },
            {
                label: 'Date',
                shortcodes: [
                    { code: '[date]', desc: "Today's date" },
                    { code: '[monthyear]', desc: 'Month and year' },
                    { code: '[dt]', desc: 'Day of month' },
                    { code: '[weekday]', desc: 'Day of week' }
                ]
            },
            {
                label: 'Post Dates',
                shortcodes: [
                    { code: '[datepublished]', desc: 'Publication date' },
                    { code: '[datemodified]', desc: 'Modified date' }
                ]
            },
            {
                label: 'Events',
                shortcodes: [
                    { code: '[blackfriday]', desc: 'Black Friday' },
                    { code: '[cybermonday]', desc: 'Cyber Monday' }
                ]
            },
            {
                label: 'Countdown',
                shortcodes: [
                    { code: '[daysuntil date=""]', desc: 'Days until date' },
                    { code: '[dayssince date=""]', desc: 'Days since date' }
                ]
            }
        ];

        // Unregister if already registered (for hot reload)
        try {
            unregisterFormatType( 'dmyip/shortcode' );
        } catch ( e ) {
            // Ignore if not registered
        }

        // Register the format type
        registerFormatType( 'dmyip/shortcode', {
            title: 'Dynamic Date',
            tagName: 'span',
            className: 'dmyip-shortcode',
            edit: function( props ) {
                var value = props.value;
                var onChange = props.onChange;
                var isActive = props.isActive;

                var stateArray = useState( false );
                var isOpen = stateArray[0];
                var setIsOpen = stateArray[1];

                var togglePopover = function() {
                    setIsOpen( !isOpen );
                };

                var insertShortcode = function( shortcode ) {
                    var toInsert = create( { text: shortcode } );
                    onChange( insert( value, toInsert ) );
                    setIsOpen( false );
                };

                return createElement(
                    Fragment,
                    null,
                    createElement( RichTextToolbarButton, {
                        icon: 'calendar-alt',
                        title: 'Insert Dynamic Date',
                        onClick: togglePopover,
                        isActive: isOpen
                    } ),
                    isOpen && createElement(
                        Popover,
                        {
                            position: 'bottom center',
                            onClose: function() { setIsOpen( false ); },
                            focusOnMount: 'container'
                        },
                        createElement(
                            'div',
                            {
                                style: {
                                    padding: '12px',
                                    minWidth: '240px',
                                    maxHeight: '350px',
                                    overflowY: 'auto'
                                }
                            },
                            createElement(
                                'div',
                                {
                                    style: {
                                        fontWeight: '600',
                                        marginBottom: '12px',
                                        paddingBottom: '8px',
                                        borderBottom: '1px solid #ddd'
                                    }
                                },
                                'Insert Dynamic Date'
                            ),
                            shortcodeCategories.map( function( category, catIndex ) {
                                return createElement(
                                    'div',
                                    {
                                        key: 'cat-' + catIndex,
                                        style: { marginBottom: '10px' }
                                    },
                                    createElement(
                                        'div',
                                        {
                                            style: {
                                                fontSize: '11px',
                                                fontWeight: '600',
                                                textTransform: 'uppercase',
                                                color: '#757575',
                                                marginBottom: '4px'
                                            }
                                        },
                                        category.label
                                    ),
                                    category.shortcodes.map( function( item, itemIndex ) {
                                        return createElement(
                                            Button,
                                            {
                                                key: 'item-' + catIndex + '-' + itemIndex,
                                                variant: 'tertiary',
                                                onClick: function() { insertShortcode( item.code ); },
                                                style: {
                                                    display: 'flex',
                                                    width: '100%',
                                                    justifyContent: 'space-between',
                                                    padding: '4px 8px',
                                                    height: 'auto',
                                                    marginBottom: '2px'
                                                }
                                            },
                                            createElement(
                                                'code',
                                                {
                                                    style: {
                                                        fontSize: '11px',
                                                        background: '#f0f0f0',
                                                        padding: '2px 4px',
                                                        borderRadius: '2px'
                                                    }
                                                },
                                                item.code
                                            ),
                                            createElement(
                                                'span',
                                                {
                                                    style: {
                                                        fontSize: '11px',
                                                        color: '#757575'
                                                    }
                                                },
                                                item.desc
                                            )
                                        );
                                    } )
                                );
                            } )
                        )
                    )
                );
            }
        } );

        console.log( 'DMYIP: Dynamic Date shortcode format registered' );
    } );

} )();
