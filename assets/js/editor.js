/**
 * Dynamic Month & Year into Posts - Block Editor Integration
 *
 * Provides:
 * - Toolbar dropdown for inserting shortcodes
 * - Sidebar panel with shortcode reference
 */

( function( wp ) {
    const { registerFormatType, insert, create } = wp.richText;
    const { RichTextToolbarButton, BlockControls } = wp.blockEditor;
    const { Popover, MenuGroup, MenuItem, PanelBody, PanelRow, Button, Dropdown, ToolbarButton } = wp.components;
    const { Fragment, useState, useCallback } = wp.element;
    const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editor;
    const { registerPlugin } = wp.plugins;
    const { __ } = wp.i18n;

    // All available shortcodes organized by category
    const shortcodeCategories = {
        year: {
            label: __( 'Year', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[year]', desc: __( 'Current year', 'dynamic-month-year-into-posts' ) },
                { code: '[nyear]', desc: __( 'Next year', 'dynamic-month-year-into-posts' ) },
                { code: '[pyear]', desc: __( 'Previous year', 'dynamic-month-year-into-posts' ) },
                { code: '[nnyear]', desc: __( 'Year after next', 'dynamic-month-year-into-posts' ) },
                { code: '[ppyear]', desc: __( 'Year before previous', 'dynamic-month-year-into-posts' ) },
            ]
        },
        month: {
            label: __( 'Month', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[month]', desc: __( 'Current month (full)', 'dynamic-month-year-into-posts' ) },
                { code: '[mon]', desc: __( 'Current month (short)', 'dynamic-month-year-into-posts' ) },
                { code: '[mm]', desc: __( 'Month number (01-12)', 'dynamic-month-year-into-posts' ) },
                { code: '[mn]', desc: __( 'Month number (1-12)', 'dynamic-month-year-into-posts' ) },
                { code: '[nmonth]', desc: __( 'Next month (full)', 'dynamic-month-year-into-posts' ) },
                { code: '[pmonth]', desc: __( 'Previous month (full)', 'dynamic-month-year-into-posts' ) },
                { code: '[nmon]', desc: __( 'Next month (short)', 'dynamic-month-year-into-posts' ) },
                { code: '[pmon]', desc: __( 'Previous month (short)', 'dynamic-month-year-into-posts' ) },
            ]
        },
        date: {
            label: __( 'Date', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[date]', desc: __( 'Today\'s date', 'dynamic-month-year-into-posts' ) },
                { code: '[monthyear]', desc: __( 'Month and year', 'dynamic-month-year-into-posts' ) },
                { code: '[nmonthyear]', desc: __( 'Next month and year', 'dynamic-month-year-into-posts' ) },
                { code: '[pmonthyear]', desc: __( 'Previous month and year', 'dynamic-month-year-into-posts' ) },
                { code: '[dt]', desc: __( 'Day of month', 'dynamic-month-year-into-posts' ) },
                { code: '[nd]', desc: __( 'Tomorrow\'s day', 'dynamic-month-year-into-posts' ) },
                { code: '[pd]', desc: __( 'Yesterday\'s day', 'dynamic-month-year-into-posts' ) },
            ]
        },
        weekday: {
            label: __( 'Weekday', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[weekday]', desc: __( 'Day of week (full)', 'dynamic-month-year-into-posts' ) },
                { code: '[wd]', desc: __( 'Day of week (short)', 'dynamic-month-year-into-posts' ) },
            ]
        },
        post: {
            label: __( 'Post Dates', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[datepublished]', desc: __( 'Post publication date', 'dynamic-month-year-into-posts' ) },
                { code: '[datemodified]', desc: __( 'Post modified date', 'dynamic-month-year-into-posts' ) },
            ]
        },
        events: {
            label: __( 'Events', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[blackfriday]', desc: __( 'Black Friday date', 'dynamic-month-year-into-posts' ) },
                { code: '[cybermonday]', desc: __( 'Cyber Monday date', 'dynamic-month-year-into-posts' ) },
            ]
        },
        countdown: {
            label: __( 'Countdown', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[daysuntil date="YYYY-MM-DD"]', desc: __( 'Days until date', 'dynamic-month-year-into-posts' ) },
                { code: '[dayssince date="YYYY-MM-DD"]', desc: __( 'Days since date', 'dynamic-month-year-into-posts' ) },
            ]
        }
    };

    // Calendar icon SVG
    const CalendarIcon = function() {
        return wp.element.createElement(
            'svg',
            {
                xmlns: 'http://www.w3.org/2000/svg',
                viewBox: '0 0 24 24',
                width: '24',
                height: '24',
                fill: 'currentColor'
            },
            wp.element.createElement( 'path', {
                d: 'M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7v-5z'
            } )
        );
    };

    /**
     * Copy shortcode to clipboard
     */
    function copyToClipboard( text, setCopied ) {
        navigator.clipboard.writeText( text ).then( function() {
            setCopied( text );
            setTimeout( function() {
                setCopied( '' );
            }, 2000 );
        } );
    }

    /**
     * Shortcode Menu Content Component
     */
    const ShortcodeMenuContent = function( { onInsert, onClose } ) {
        return wp.element.createElement(
            'div',
            {
                className: 'dmyip-shortcode-menu',
                style: {
                    padding: '8px 0',
                    minWidth: '280px',
                    maxHeight: '400px',
                    overflowY: 'auto'
                }
            },
            Object.keys( shortcodeCategories ).map( function( categoryKey ) {
                const category = shortcodeCategories[ categoryKey ];
                return wp.element.createElement(
                    MenuGroup,
                    {
                        key: categoryKey,
                        label: category.label
                    },
                    category.shortcodes.map( function( item ) {
                        return wp.element.createElement(
                            MenuItem,
                            {
                                key: item.code,
                                onClick: function() {
                                    onInsert( item.code );
                                    onClose();
                                },
                                info: item.desc
                            },
                            wp.element.createElement( 'code', null, item.code )
                        );
                    } )
                );
            } )
        );
    };

    /**
     * Toolbar Button with Dropdown
     */
    const DynamicDateToolbarButton = function( { isActive, value, onChange, contentRef } ) {
        const onInsertShortcode = useCallback( function( shortcode ) {
            // Insert the shortcode text at the current cursor position
            const newValue = insert( value, create( { text: shortcode } ) );
            onChange( newValue );
        }, [ value, onChange ] );

        return wp.element.createElement(
            Dropdown,
            {
                className: 'dmyip-toolbar-dropdown',
                contentClassName: 'dmyip-toolbar-dropdown-content',
                popoverProps: {
                    placement: 'bottom-start'
                },
                renderToggle: function( { isOpen, onToggle } ) {
                    return wp.element.createElement(
                        RichTextToolbarButton,
                        {
                            icon: CalendarIcon,
                            title: __( 'Insert Dynamic Date', 'dynamic-month-year-into-posts' ),
                            onClick: onToggle,
                            isActive: isOpen
                        }
                    );
                },
                renderContent: function( { onClose } ) {
                    return wp.element.createElement(
                        ShortcodeMenuContent,
                        {
                            onInsert: onInsertShortcode,
                            onClose: onClose
                        }
                    );
                }
            }
        );
    };

    // Register the format type for the toolbar button
    registerFormatType( 'dmyip/dynamic-date', {
        title: __( 'Dynamic Date', 'dynamic-month-year-into-posts' ),
        tagName: 'span',
        className: null,
        edit: DynamicDateToolbarButton
    } );

    /**
     * Sidebar Panel Component
     */
    const DynamicDateSidebar = function() {
        const [ copied, setCopied ] = useState( '' );

        return wp.element.createElement(
            Fragment,
            null,
            wp.element.createElement(
                PluginSidebarMoreMenuItem,
                {
                    target: 'dmyip-sidebar',
                    icon: CalendarIcon
                },
                __( 'Dynamic Dates', 'dynamic-month-year-into-posts' )
            ),
            wp.element.createElement(
                PluginSidebar,
                {
                    name: 'dmyip-sidebar',
                    icon: CalendarIcon,
                    title: __( 'Dynamic Date Shortcodes', 'dynamic-month-year-into-posts' )
                },
                Object.keys( shortcodeCategories ).map( function( categoryKey ) {
                    const category = shortcodeCategories[ categoryKey ];
                    return wp.element.createElement(
                        PanelBody,
                        {
                            key: categoryKey,
                            title: category.label,
                            initialOpen: categoryKey === 'year'
                        },
                        category.shortcodes.map( function( item ) {
                            return wp.element.createElement(
                                PanelRow,
                                {
                                    key: item.code,
                                    className: 'dmyip-shortcode-row'
                                },
                                wp.element.createElement(
                                    'div',
                                    { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', width: '100%', gap: '8px' } },
                                    wp.element.createElement(
                                        'div',
                                        { style: { flex: 1, minWidth: 0 } },
                                        wp.element.createElement( 'code', { style: { fontSize: '11px', wordBreak: 'break-all' } }, item.code ),
                                        wp.element.createElement( 'div', { style: { fontSize: '11px', opacity: 0.7, marginTop: '2px' } }, item.desc )
                                    ),
                                    wp.element.createElement(
                                        Button,
                                        {
                                            variant: 'secondary',
                                            size: 'small',
                                            onClick: function() {
                                                copyToClipboard( item.code, setCopied );
                                            }
                                        },
                                        copied === item.code ? __( 'Copied!', 'dynamic-month-year-into-posts' ) : __( 'Copy', 'dynamic-month-year-into-posts' )
                                    )
                                )
                            );
                        } )
                    );
                } ),
                wp.element.createElement(
                    PanelBody,
                    {
                        title: __( 'Usage Tips', 'dynamic-month-year-into-posts' ),
                        initialOpen: false
                    },
                    wp.element.createElement(
                        'p',
                        { style: { fontSize: '12px', margin: '0 0 8px' } },
                        __( 'Use [year n=5] for offset years. Example: [year n=-2] shows 2 years ago.', 'dynamic-month-year-into-posts' )
                    ),
                    wp.element.createElement(
                        'p',
                        { style: { fontSize: '12px', margin: 0 } },
                        __( 'Capitalize shortcodes: [cmonth], [cmon], [cnmonth], [cpmonth]', 'dynamic-month-year-into-posts' )
                    )
                )
            )
        );
    };

    // Register the sidebar plugin
    registerPlugin( 'dmyip-sidebar', {
        render: DynamicDateSidebar,
        icon: CalendarIcon
    } );

} )( window.wp );
