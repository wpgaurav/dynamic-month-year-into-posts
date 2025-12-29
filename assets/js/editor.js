/**
 * Dynamic Month & Year into Posts - Block Editor Integration
 *
 * Adds a toolbar button to insert dynamic date shortcodes.
 * Based on WordPress Format API pattern.
 */

( function() {
    'use strict';

    var __ = wp.i18n.__;
    var registerFormatType = wp.richText.registerFormatType;
    var insert = wp.richText.insert;
    var create = wp.richText.create;
    var Fragment = wp.element.Fragment;
    var createElement = wp.element.createElement;
    var Component = wp.element.Component;
    var BlockControls = wp.blockEditor.BlockControls;
    var Toolbar = wp.components.Toolbar;
    var ToolbarButton = wp.components.ToolbarButton;
    var Popover = wp.components.Popover;
    var Button = wp.components.Button;

    // Shortcode categories with all available shortcodes
    var shortcodeCategories = [
        {
            key: 'year',
            label: __( 'Year', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[year]', desc: __( 'Current year', 'dynamic-month-year-into-posts' ) },
                { code: '[nyear]', desc: __( 'Next year', 'dynamic-month-year-into-posts' ) },
                { code: '[pyear]', desc: __( 'Previous year', 'dynamic-month-year-into-posts' ) }
            ]
        },
        {
            key: 'month',
            label: __( 'Month', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[month]', desc: __( 'Current month', 'dynamic-month-year-into-posts' ) },
                { code: '[mon]', desc: __( 'Month (short)', 'dynamic-month-year-into-posts' ) },
                { code: '[nmonth]', desc: __( 'Next month', 'dynamic-month-year-into-posts' ) },
                { code: '[pmonth]', desc: __( 'Previous month', 'dynamic-month-year-into-posts' ) }
            ]
        },
        {
            key: 'date',
            label: __( 'Date', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[date]', desc: __( "Today's date", 'dynamic-month-year-into-posts' ) },
                { code: '[monthyear]', desc: __( 'Month and year', 'dynamic-month-year-into-posts' ) },
                { code: '[dt]', desc: __( 'Day of month', 'dynamic-month-year-into-posts' ) },
                { code: '[weekday]', desc: __( 'Day of week', 'dynamic-month-year-into-posts' ) }
            ]
        },
        {
            key: 'post',
            label: __( 'Post Dates', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[datepublished]', desc: __( 'Publication date', 'dynamic-month-year-into-posts' ) },
                { code: '[datemodified]', desc: __( 'Modified date', 'dynamic-month-year-into-posts' ) }
            ]
        },
        {
            key: 'events',
            label: __( 'Events', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[blackfriday]', desc: __( 'Black Friday', 'dynamic-month-year-into-posts' ) },
                { code: '[cybermonday]', desc: __( 'Cyber Monday', 'dynamic-month-year-into-posts' ) }
            ]
        },
        {
            key: 'countdown',
            label: __( 'Countdown', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[daysuntil date=""]', desc: __( 'Days until date', 'dynamic-month-year-into-posts' ) },
                { code: '[dayssince date=""]', desc: __( 'Days since date', 'dynamic-month-year-into-posts' ) }
            ]
        }
    ];

    /**
     * Calendar SVG Icon
     */
    var CalendarIcon = createElement(
        'svg',
        {
            xmlns: 'http://www.w3.org/2000/svg',
            viewBox: '0 0 24 24',
            width: '24',
            height: '24'
        },
        createElement( 'path', {
            d: 'M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM9 10H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm-8 4H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2z'
        } )
    );

    /**
     * Dynamic Date Toolbar Button Component
     */
    var DynamicDateToolbarButton = function( props ) {
        var value = props.value;
        var onChange = props.onChange;
        var isActive = props.isActive;

        // Use React's useState through wp.element
        var stateArray = wp.element.useState( false );
        var isOpen = stateArray[0];
        var setIsOpen = stateArray[1];

        /**
         * Insert shortcode into the editor
         */
        var insertShortcode = function( shortcode ) {
            var toInsert = create( { text: shortcode } );
            var newValue = insert( value, toInsert );
            onChange( newValue );
            setIsOpen( false );
        };

        /**
         * Toggle popover visibility
         */
        var togglePopover = function() {
            setIsOpen( ! isOpen );
        };

        /**
         * Close popover
         */
        var closePopover = function() {
            setIsOpen( false );
        };

        return createElement(
            Fragment,
            null,
            createElement(
                BlockControls,
                { group: 'other' },
                createElement(
                    Toolbar,
                    null,
                    createElement(
                        ToolbarButton,
                        {
                            icon: CalendarIcon,
                            label: __( 'Insert Dynamic Date', 'dynamic-month-year-into-posts' ),
                            onClick: togglePopover,
                            isPressed: isOpen
                        }
                    )
                )
            ),
            isOpen && createElement(
                Popover,
                {
                    position: 'bottom center',
                    onClose: closePopover,
                    focusOnMount: 'container'
                },
                createElement(
                    'div',
                    {
                        className: 'dmyip-shortcode-picker',
                        style: {
                            padding: '12px',
                            minWidth: '260px',
                            maxHeight: '400px',
                            overflowY: 'auto'
                        }
                    },
                    createElement(
                        'div',
                        {
                            style: {
                                fontSize: '13px',
                                fontWeight: '600',
                                marginBottom: '12px',
                                paddingBottom: '8px',
                                borderBottom: '1px solid #ddd'
                            }
                        },
                        __( 'Insert Dynamic Date', 'dynamic-month-year-into-posts' )
                    ),
                    shortcodeCategories.map( function( category ) {
                        return createElement(
                            'div',
                            {
                                key: category.key,
                                style: { marginBottom: '12px' }
                            },
                            createElement(
                                'div',
                                {
                                    style: {
                                        fontSize: '11px',
                                        fontWeight: '600',
                                        textTransform: 'uppercase',
                                        color: '#757575',
                                        marginBottom: '6px'
                                    }
                                },
                                category.label
                            ),
                            category.shortcodes.map( function( item ) {
                                return createElement(
                                    Button,
                                    {
                                        key: item.code,
                                        variant: 'tertiary',
                                        onClick: function() {
                                            insertShortcode( item.code );
                                        },
                                        style: {
                                            display: 'flex',
                                            width: '100%',
                                            justifyContent: 'space-between',
                                            alignItems: 'center',
                                            padding: '6px 8px',
                                            height: 'auto',
                                            marginBottom: '2px',
                                            textAlign: 'left'
                                        }
                                    },
                                    createElement(
                                        'code',
                                        {
                                            style: {
                                                fontSize: '11px',
                                                background: '#f0f0f0',
                                                padding: '2px 6px',
                                                borderRadius: '3px',
                                                fontFamily: 'monospace'
                                            }
                                        },
                                        item.code
                                    ),
                                    createElement(
                                        'span',
                                        {
                                            style: {
                                                fontSize: '11px',
                                                color: '#757575',
                                                marginLeft: '8px'
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
    };

    /**
     * Register format type when DOM is ready
     */
    wp.domReady( function() {
        registerFormatType( 'dmyip/dynamic-date', {
            title: __( 'Dynamic Date', 'dynamic-month-year-into-posts' ),
            tagName: 'span',
            className: null,
            edit: DynamicDateToolbarButton
        } );
    } );

} )();
