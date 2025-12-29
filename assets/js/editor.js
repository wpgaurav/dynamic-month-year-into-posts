/**
 * Dynamic Month & Year into Posts - Block Editor Integration
 *
 * Adds a toolbar button to insert dynamic date shortcodes.
 */

( function() {
    // Check if required dependencies exist
    if ( ! wp || ! wp.richText || ! wp.blockEditor || ! wp.components || ! wp.element ) {
        return;
    }

    var registerFormatType = wp.richText.registerFormatType;
    var insert = wp.richText.insert;
    var create = wp.richText.create;
    var RichTextToolbarButton = wp.blockEditor.RichTextToolbarButton;
    var Popover = wp.components.Popover;
    var Button = wp.components.Button;
    var useState = wp.element.useState;
    var useCallback = wp.element.useCallback;
    var Fragment = wp.element.Fragment;
    var createElement = wp.element.createElement;
    var __ = wp.i18n.__;

    // All available shortcodes organized by category
    var shortcodeCategories = [
        {
            key: 'year',
            label: 'Year',
            shortcodes: [
                { code: '[year]', desc: 'Current year' },
                { code: '[nyear]', desc: 'Next year' },
                { code: '[pyear]', desc: 'Previous year' },
            ]
        },
        {
            key: 'month',
            label: 'Month',
            shortcodes: [
                { code: '[month]', desc: 'Current month' },
                { code: '[mon]', desc: 'Month (short)' },
                { code: '[nmonth]', desc: 'Next month' },
                { code: '[pmonth]', desc: 'Previous month' },
            ]
        },
        {
            key: 'date',
            label: 'Date',
            shortcodes: [
                { code: '[date]', desc: 'Today\'s date' },
                { code: '[monthyear]', desc: 'Month and year' },
                { code: '[dt]', desc: 'Day of month' },
                { code: '[weekday]', desc: 'Day of week' },
            ]
        },
        {
            key: 'post',
            label: 'Post Dates',
            shortcodes: [
                { code: '[datepublished]', desc: 'Publication date' },
                { code: '[datemodified]', desc: 'Modified date' },
            ]
        },
        {
            key: 'events',
            label: 'Events',
            shortcodes: [
                { code: '[blackfriday]', desc: 'Black Friday' },
                { code: '[cybermonday]', desc: 'Cyber Monday' },
            ]
        },
        {
            key: 'countdown',
            label: 'Countdown',
            shortcodes: [
                { code: '[daysuntil date=""]', desc: 'Days until date' },
                { code: '[dayssince date=""]', desc: 'Days since date' },
            ]
        }
    ];

    // Calendar icon as a string identifier (WordPress dashicon)
    var calendarIcon = 'calendar-alt';

    /**
     * Toolbar Button Component for RichText
     */
    var DynamicDateButton = function( props ) {
        var value = props.value;
        var onChange = props.onChange;
        var isOpenState = useState( false );
        var isOpen = isOpenState[0];
        var setIsOpen = isOpenState[1];

        var insertShortcode = function( shortcode ) {
            // Create a new value with the shortcode text inserted
            var toInsert = create( { text: shortcode } );
            var newValue = insert( value, toInsert );
            onChange( newValue );
            setIsOpen( false );
        };

        return createElement(
            Fragment,
            null,
            createElement(
                RichTextToolbarButton,
                {
                    icon: calendarIcon,
                    title: 'Dynamic Date',
                    onClick: function() {
                        setIsOpen( ! isOpen );
                    },
                    isActive: isOpen
                }
            ),
            isOpen && createElement(
                Popover,
                {
                    position: 'bottom center',
                    onClose: function() {
                        setIsOpen( false );
                    }
                },
                createElement(
                    'div',
                    {
                        style: {
                            padding: '12px',
                            minWidth: '240px',
                            maxHeight: '400px',
                            overflowY: 'auto'
                        }
                    },
                    shortcodeCategories.map( function( category ) {
                        return createElement(
                            'div',
                            { key: category.key, style: { marginBottom: '16px' } },
                            createElement(
                                'div',
                                {
                                    style: {
                                        fontSize: '11px',
                                        fontWeight: '600',
                                        textTransform: 'uppercase',
                                        color: '#757575',
                                        marginBottom: '8px'
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
                                            padding: '8px',
                                            height: 'auto',
                                            marginBottom: '4px'
                                        }
                                    },
                                    createElement( 'code', { style: { fontSize: '12px', background: '#f0f0f0', padding: '2px 6px', borderRadius: '3px' } }, item.code ),
                                    createElement( 'span', { style: { fontSize: '11px', color: '#757575' } }, item.desc )
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
        title: 'Dynamic Date',
        tagName: 'span',
        className: null,
        edit: DynamicDateButton
    } );

} )();
