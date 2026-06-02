/**
 * Dynamic Month & Year into Posts - Block Editor Integration
 *
 * Adds a toolbar button to insert dynamic date shortcodes.
 */

( function () {
	'use strict';

	// Wait for DOM ready
	wp.domReady( function () {
		const __ = wp.i18n.__;
		const registerFormatType = wp.richText.registerFormatType;
		const unregisterFormatType = wp.richText.unregisterFormatType;
		const insert = wp.richText.insert;
		const create = wp.richText.create;
		const createElement = wp.element.createElement;
		const useState = wp.element.useState;
		const Fragment = wp.element.Fragment;
		const RichTextToolbarButton = wp.blockEditor.RichTextToolbarButton;
		const Popover = wp.components.Popover;
		const Button = wp.components.Button;

		// Shortcode categories.
		const shortcodeCategories = [
			{
				label: __( 'Year', 'dynamic-month-year-into-posts' ),
				shortcodes: [
					{
						code: '[year]',
						desc: __(
							'Current year',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[nyear]',
						desc: __(
							'Next year',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[pyear]',
						desc: __(
							'Previous year',
							'dynamic-month-year-into-posts'
						),
					},
				],
			},
			{
				label: __( 'Month', 'dynamic-month-year-into-posts' ),
				shortcodes: [
					{
						code: '[month]',
						desc: __(
							'Current month',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[mon]',
						desc: __(
							'Month (short)',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[nmonth]',
						desc: __(
							'Next month',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[pmonth]',
						desc: __(
							'Previous month',
							'dynamic-month-year-into-posts'
						),
					},
				],
			},
			{
				label: __( 'Date', 'dynamic-month-year-into-posts' ),
				shortcodes: [
					{
						code: '[date]',
						desc: __(
							"Today's date",
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[monthyear]',
						desc: __(
							'Month and year',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[dt]',
						desc: __(
							'Day of month',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[weekday]',
						desc: __(
							'Day of week',
							'dynamic-month-year-into-posts'
						),
					},
				],
			},
			{
				label: __( 'Post Dates', 'dynamic-month-year-into-posts' ),
				shortcodes: [
					{
						code: '[datepublished]',
						desc: __(
							'Publication date',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[datemodified]',
						desc: __(
							'Modified date',
							'dynamic-month-year-into-posts'
						),
					},
				],
			},
			{
				label: __( 'Events', 'dynamic-month-year-into-posts' ),
				shortcodes: [
					{
						code: '[blackfriday]',
						desc: __(
							'Black Friday',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[cybermonday]',
						desc: __(
							'Cyber Monday',
							'dynamic-month-year-into-posts'
						),
					},
				],
			},
			{
				label: __( 'Countdown', 'dynamic-month-year-into-posts' ),
				shortcodes: [
					{
						code: '[daysuntil date=""]',
						desc: __(
							'Days until date',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[dayssince date=""]',
						desc: __(
							'Days since date',
							'dynamic-month-year-into-posts'
						),
					},
				],
			},
			{
				label: __( 'Age', 'dynamic-month-year-into-posts' ),
				shortcodes: [
					{
						code: '[age date=""]',
						desc: __(
							'Age in years',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[age date="" ordinal="true"]',
						desc: __(
							'Age with suffix',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[age date="" format="ym"]',
						desc: __(
							'Years and months',
							'dynamic-month-year-into-posts'
						),
					},
				],
			},
			{
				label: __( 'Season', 'dynamic-month-year-into-posts' ),
				shortcodes: [
					{
						code: '[season]',
						desc: __(
							'Current season',
							'dynamic-month-year-into-posts'
						),
					},
					{
						code: '[season region="south"]',
						desc: __(
							'Southern hemisphere',
							'dynamic-month-year-into-posts'
						),
					},
				],
			},
		];

		// Unregister if already registered (for hot reload).
		try {
			unregisterFormatType( 'dmyip/shortcode' );
		} catch {
			// Ignore if not registered.
		}

		function DynamicDateFormatEdit( props ) {
			const value = props.value;
			const onChange = props.onChange;
			const stateArray = useState( false );
			const isOpen = stateArray[ 0 ];
			const setIsOpen = stateArray[ 1 ];

			const insertShortcode = function ( shortcode ) {
				const toInsert = create( { text: shortcode } );
				onChange( insert( value, toInsert ) );
				setIsOpen( false );
			};

			return createElement(
				Fragment,
				null,
				createElement( RichTextToolbarButton, {
					icon: 'calendar-alt',
					title: __(
						'Insert Dynamic Date',
						'dynamic-month-year-into-posts'
					),
					onClick() {
						setIsOpen( ! isOpen );
					},
					isActive: isOpen,
				} ),
				isOpen &&
					createElement(
						Popover,
						{
							position: 'bottom center',
							onClose() {
								setIsOpen( false );
							},
							focusOnMount: 'container',
						},
						createElement(
							'div',
							{
								style: {
									padding: '12px',
									minWidth: '260px',
									maxHeight: '350px',
									overflowY: 'auto',
								},
							},
							createElement(
								'div',
								{
									style: {
										fontWeight: '600',
										marginBottom: '12px',
										paddingBottom: '8px',
										borderBottom: '1px solid #ddd',
									},
								},
								__(
									'Insert Dynamic Date',
									'dynamic-month-year-into-posts'
								)
							),
							shortcodeCategories.map(
								function ( category, catIndex ) {
									return createElement(
										'div',
										{
											key: 'cat-' + catIndex,
											style: { marginBottom: '10px' },
										},
										createElement(
											'div',
											{
												style: {
													fontSize: '11px',
													fontWeight: '600',
													textTransform: 'uppercase',
													color: '#757575',
													marginBottom: '4px',
												},
											},
											category.label
										),
										category.shortcodes.map(
											function ( item, itemIndex ) {
												return createElement(
													Button,
													{
														key:
															'item-' +
															catIndex +
															'-' +
															itemIndex,
														variant: 'tertiary',
														onClick() {
															insertShortcode(
																item.code
															);
														},
														style: {
															display: 'flex',
															width: '100%',
															justifyContent:
																'space-between',
															padding: '4px 8px',
															height: 'auto',
															marginBottom: '2px',
														},
													},
													createElement(
														'code',
														{
															style: {
																fontSize:
																	'11px',
																background:
																	'#f0f0f0',
																padding:
																	'2px 4px',
																borderRadius:
																	'2px',
															},
														},
														item.code
													),
													createElement(
														'span',
														{
															style: {
																fontSize:
																	'11px',
																color: '#757575',
															},
														},
														item.desc
													)
												);
											}
										)
									);
								}
							)
						)
					)
			);
		}

		registerFormatType( 'dmyip/shortcode', {
			title: __( 'Dynamic Date', 'dynamic-month-year-into-posts' ),
			tagName: 'span',
			className: 'dmyip-shortcode',
			edit: DynamicDateFormatEdit,
		} );
	} );
} )();
