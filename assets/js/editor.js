/**
 * Dynamic Month & Year into Posts - Block Editor Integration
 *
 * Provides:
 * - Toolbar dropdown for inserting shortcodes
 * - Sidebar panel with shortcode reference
 */

( function() {
    const { registerFormatType, insert, create } = wp.richText;
    const { RichTextToolbarButton } = wp.blockEditor;
    const { Popover, Button, MenuGroup, MenuItem, PanelBody, PanelRow } = wp.components;
    const { useState, useCallback, Fragment } = wp.element;
    const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editor || wp.editPost;
    const { registerPlugin } = wp.plugins;
    const { __ } = wp.i18n;

    // All available shortcodes organized by category
    const shortcodeCategories = [
        {
            key: 'year',
            label: __( 'Year', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[year]', desc: __( 'Current year', 'dynamic-month-year-into-posts' ) },
                { code: '[nyear]', desc: __( 'Next year', 'dynamic-month-year-into-posts' ) },
                { code: '[pyear]', desc: __( 'Previous year', 'dynamic-month-year-into-posts' ) },
                { code: '[nnyear]', desc: __( 'Year after next', 'dynamic-month-year-into-posts' ) },
                { code: '[ppyear]', desc: __( 'Year before previous', 'dynamic-month-year-into-posts' ) },
            ]
        },
        {
            key: 'month',
            label: __( 'Month', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[month]', desc: __( 'Current month (full)', 'dynamic-month-year-into-posts' ) },
                { code: '[mon]', desc: __( 'Current month (short)', 'dynamic-month-year-into-posts' ) },
                { code: '[mm]', desc: __( 'Month number (01-12)', 'dynamic-month-year-into-posts' ) },
                { code: '[nmonth]', desc: __( 'Next month', 'dynamic-month-year-into-posts' ) },
                { code: '[pmonth]', desc: __( 'Previous month', 'dynamic-month-year-into-posts' ) },
            ]
        },
        {
            key: 'date',
            label: __( 'Date', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[date]', desc: __( 'Today\'s date', 'dynamic-month-year-into-posts' ) },
                { code: '[monthyear]', desc: __( 'Month and year', 'dynamic-month-year-into-posts' ) },
                { code: '[dt]', desc: __( 'Day of month', 'dynamic-month-year-into-posts' ) },
                { code: '[weekday]', desc: __( 'Day of week', 'dynamic-month-year-into-posts' ) },
            ]
        },
        {
            key: 'post',
            label: __( 'Post Dates', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[datepublished]', desc: __( 'Publication date', 'dynamic-month-year-into-posts' ) },
                { code: '[datemodified]', desc: __( 'Modified date', 'dynamic-month-year-into-posts' ) },
            ]
        },
        {
            key: 'events',
            label: __( 'Events', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[blackfriday]', desc: __( 'Black Friday', 'dynamic-month-year-into-posts' ) },
                { code: '[cybermonday]', desc: __( 'Cyber Monday', 'dynamic-month-year-into-posts' ) },
            ]
        },
        {
            key: 'countdown',
            label: __( 'Countdown', 'dynamic-month-year-into-posts' ),
            shortcodes: [
                { code: '[daysuntil date=""]', desc: __( 'Days until date', 'dynamic-month-year-into-posts' ) },
                { code: '[dayssince date=""]', desc: __( 'Days since date', 'dynamic-month-year-into-posts' ) },
            ]
        }
    ];

    // Calendar SVG icon
    const calendarIcon = wp.element.createElement( 'svg', {
        xmlns: 'http://www.w3.org/2000/svg',
        viewBox: '0 0 24 24',
        width: 24,
        height: 24
    }, wp.element.createElement( 'path', {
        fill: 'currentColor',
        d: 'M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7v-5z'
    } ) );

    /**
     * Toolbar Button Component for RichText
     */
    const DynamicDateButton = function( { isActive, value, onChange } ) {
        const [ isOpen, setIsOpen ] = useState( false );

        const insertShortcode = useCallback( function( shortcode ) {
            // Create a new value with the shortcode text inserted
            const toInsert = create( { text: shortcode } );
            const newValue = insert( value, toInsert );
            onChange( newValue );
            setIsOpen( false );
        }, [ value, onChange ] );

        return wp.element.createElement(
            Fragment,
            null,
            wp.element.createElement(
                RichTextToolbarButton,
                {
                    icon: calendarIcon,
                    title: __( 'Dynamic Date', 'dynamic-month-year-into-posts' ),
                    onClick: function() {
                        setIsOpen( ! isOpen );
                    },
                    isActive: isOpen
                }
            ),
            isOpen && wp.element.createElement(
                Popover,
                {
                    position: 'bottom center',
                    onClose: function() {
                        setIsOpen( false );
                    },
                    focusOnMount: 'container'
                },
                wp.element.createElement(
                    'div',
                    {
                        style: {
                            padding: '8px',
                            minWidth: '220px',
                            maxHeight: '350px',
                            overflowY: 'auto'
                        }
                    },
                    shortcodeCategories.map( function( category ) {
                        return wp.element.createElement(
                            'div',
                            { key: category.key, style: { marginBottom: '12px' } },
                            wp.element.createElement(
                                'div',
                                {
                                    style: {
                                        fontSize: '11px',
                                        fontWeight: '600',
                                        textTransform: 'uppercase',
                                        color: '#757575',
                                        marginBottom: '6px',
                                        paddingLeft: '4px'
                                    }
                                },
                                category.label
                            ),
                            category.shortcodes.map( function( item ) {
                                return wp.element.createElement(
                                    Button,
                                    {
                                        key: item.code,
                                        variant: 'tertiary',
                                        onClick: function() {
                                            insertShortcode( item.code );
                                        },
                                        style: {
                                            display: 'block',
                                            width: '100%',
                                            textAlign: 'left',
                                            padding: '6px 8px',
                                            height: 'auto'
                                        }
                                    },
                                    wp.element.createElement( 'code', { style: { fontSize: '12px' } }, item.code ),
                                    wp.element.createElement(
                                        'span',
                                        { style: { fontSize: '11px', color: '#757575', marginLeft: '8px' } },
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

    // Register format type for the toolbar button
    registerFormatType( 'dmyip/dynamic-date', {
        title: __( 'Dynamic Date', 'dynamic-month-year-into-posts' ),
        tagName: 'span',
        className: null,
        edit: DynamicDateButton
    } );

    /**
     * Sidebar Panel Component
     */
    var DynamicDateSidebar = function() {
        var copiedState = useState( '' );
        var copied = copiedState[0];
        var setCopied = copiedState[1];

        var copyToClipboard = function( text ) {
            navigator.clipboard.writeText( text ).then( function() {
                setCopied( text );
                setTimeout( function() {
                    setCopied( '' );
                }, 2000 );
            } );
        };

        // Check if PluginSidebar is available
        var SidebarComponent = wp.editor && wp.editor.PluginSidebar ? wp.editor.PluginSidebar : ( wp.editPost ? wp.editPost.PluginSidebar : null );
        var SidebarMenuItem = wp.editor && wp.editor.PluginSidebarMoreMenuItem ? wp.editor.PluginSidebarMoreMenuItem : ( wp.editPost ? wp.editPost.PluginSidebarMoreMenuItem : null );

        if ( ! SidebarComponent || ! SidebarMenuItem ) {
            return null;
        }

        return wp.element.createElement(
            Fragment,
            null,
            wp.element.createElement(
                SidebarMenuItem,
                {
                    target: 'dmyip-sidebar',
                    icon: calendarIcon
                },
                __( 'Dynamic Dates', 'dynamic-month-year-into-posts' )
            ),
            wp.element.createElement(
                SidebarComponent,
                {
                    name: 'dmyip-sidebar',
                    icon: calendarIcon,
                    title: __( 'Dynamic Date Shortcodes', 'dynamic-month-year-into-posts' )
                },
                shortcodeCategories.map( function( category ) {
                    return wp.element.createElement(
                        PanelBody,
                        {
                            key: category.key,
                            title: category.label,
                            initialOpen: category.key === 'year'
                        },
                        category.shortcodes.map( function( item ) {
                            return wp.element.createElement(
                                PanelRow,
                                { key: item.code },
                                wp.element.createElement(
                                    'div',
                                    { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', width: '100%', gap: '8px' } },
                                    wp.element.createElement(
                                        'div',
                                        { style: { flex: 1 } },
                                        wp.element.createElement( 'code', { style: { fontSize: '11px' } }, item.code ),
                                        wp.element.createElement( 'div', { style: { fontSize: '10px', color: '#757575' } }, item.desc )
                                    ),
                                    wp.element.createElement(
                                        Button,
                                        {
                                            variant: 'secondary',
                                            size: 'small',
                                            onClick: function() { copyToClipboard( item.code ); }
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
                    { title: __( 'Tips', 'dynamic-month-year-into-posts' ), initialOpen: false },
                    wp.element.createElement( 'p', { style: { fontSize: '12px', margin: 0 } },
                        __( 'Use [year n=5] for year offset. Capitalize with [cmonth], [cmon].', 'dynamic-month-year-into-posts' )
                    )
                )
            )
        );
    };

    // Register sidebar plugin
    registerPlugin( 'dmyip-sidebar', {
        render: DynamicDateSidebar,
        icon: calendarIcon
    } );

} )();
