/**
 * Dynamic Month & Year into Posts editor integration.
 */

import { registerBlockBindingsSource } from '@wordpress/blocks';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { Button, Popover, SearchControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __, _n, sprintf } from '@wordpress/i18n';
import { calendar } from '@wordpress/icons';
import {
	applyFormat,
	create,
	insert,
	registerFormatType,
} from '@wordpress/rich-text';

import './editor.css';

const FORMAT_TYPE = 'dmyip/shortcode';
const editorData = window.dmyipEditorData || { bindingValues: {} };

const SHORTCODE_CATEGORIES = [
	{
		label: __( 'Years', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{
				code: '[year]',
				desc: __( 'Current year', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[year n="5"]',
				desc: __(
					'Year with an offset',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[nyear]',
				desc: __( 'Next year', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[nnyear]',
				desc: __( 'Year after next', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[pyear]',
				desc: __( 'Previous year', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[ppyear]',
				desc: __( 'Two years ago', 'dynamic-month-year-into-posts' ),
			},
		],
	},
	{
		label: __( 'Months', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{
				code: '[month]',
				desc: __( 'Current month', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[cmonth]',
				desc: __(
					'Uppercase current month',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[mon]',
				desc: __( 'Short month', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[cmon]',
				desc: __(
					'Uppercase short month',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[mm]',
				desc: __(
					'Zero-padded month number',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[mn]',
				desc: __( 'Month number', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[nmonth]',
				desc: __( 'Next month', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[cnmonth]',
				desc: __(
					'Uppercase next month',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[nmon]',
				desc: __( 'Short next month', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[cnmon]',
				desc: __(
					'Uppercase short next month',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[pmonth]',
				desc: __( 'Previous month', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[cpmonth]',
				desc: __(
					'Uppercase previous month',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[pmon]',
				desc: __(
					'Short previous month',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[cpmon]',
				desc: __(
					'Uppercase short previous month',
					'dynamic-month-year-into-posts'
				),
			},
		],
	},
	{
		label: __( 'Dates and Days', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{
				code: '[monthyear]',
				desc: __( 'Month and year', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[nmonthyear]',
				desc: __(
					'Next month and year',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[pmonthyear]',
				desc: __(
					'Previous month and year',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[date]',
				desc: __( "Today's date", 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[dt]',
				desc: __(
					'Current day number',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[nd]',
				desc: __( 'Next day number', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[pd]',
				desc: __(
					'Previous day number',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[weekday]',
				desc: __( 'Weekday', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[wd]',
				desc: __( 'Short weekday', 'dynamic-month-year-into-posts' ),
			},
		],
	},
	{
		label: __( 'Post Dates', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{
				code: '[datepublished]',
				desc: __( 'Publication date', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[datemodified]',
				desc: __( 'Modified date', 'dynamic-month-year-into-posts' ),
			},
		],
	},
	{
		label: __( 'Countdown and Age', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{
				code: '[daysuntil date="YYYY-MM-DD"]',
				desc: __( 'Days until date', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[dayssince date="YYYY-MM-DD"]',
				desc: __( 'Days since date', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[age date="YYYY-MM-DD"]',
				desc: __( 'Age in years', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[age date="YYYY-MM-DD" format="ymd"]',
				desc: __( 'Full age', 'dynamic-month-year-into-posts' ),
			},
		],
	},
	{
		label: __( 'Events and Seasons', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{
				code: '[blackfriday]',
				desc: __( 'Black Friday', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[cybermonday]',
				desc: __( 'Cyber Monday', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[season]',
				desc: __( 'Season (north)', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[season region="south"]',
				desc: __( 'Season (south)', 'dynamic-month-year-into-posts' ),
			},
		],
	},
];

/**
 * Toolbar popover for inserting highlighted shortcode tokens.
 *
 * @param {Object}   props          Component props.
 * @param {Object}   props.value    RichText value.
 * @param {Function} props.onChange RichText change handler.
 * @return {Element} Toolbar component.
 */
function DynamicDateFormatEdit( { value, onChange } ) {
	const [ isOpen, setIsOpen ] = useState( false );
	const [ query, setQuery ] = useState( '' );
	const normalizedQuery = query.trim().toLocaleLowerCase();
	const filteredCategories = SHORTCODE_CATEGORIES.map( ( category ) => ( {
		...category,
		shortcodes: category.shortcodes.filter( ( item ) =>
			`${ category.label } ${ item.code } ${ item.desc }`
				.toLocaleLowerCase()
				.includes( normalizedQuery )
		),
	} ) ).filter( ( category ) => category.shortcodes.length > 0 );
	const resultCount = filteredCategories.reduce(
		( total, category ) => total + category.shortcodes.length,
		0
	);

	const insertShortcode = ( shortcode ) => {
		const start = value.start ?? 0;
		const inserted = insert( value, create( { text: shortcode } ) );
		const highlighted = applyFormat(
			inserted,
			{ type: FORMAT_TYPE },
			start,
			start + shortcode.length
		);

		onChange( highlighted );
		setIsOpen( false );
		setQuery( '' );
	};

	return (
		<>
			<RichTextToolbarButton
				icon={ calendar }
				title={ __(
					'Insert Dynamic Date',
					'dynamic-month-year-into-posts'
				) }
				onClick={ () => {
					setIsOpen( ! isOpen );

					if ( isOpen ) {
						setQuery( '' );
					}
				} }
				isActive={ isOpen }
			/>
			{ isOpen && (
				<Popover
					placement="bottom-start"
					onClose={ () => {
						setIsOpen( false );
						setQuery( '' );
					} }
					focusOnMount="firstElement"
					shift
				>
					<div
						className="dmyip-shortcode-picker"
						role="dialog"
						aria-label={ __(
							'Insert Dynamic Date',
							'dynamic-month-year-into-posts'
						) }
					>
						<div className="dmyip-shortcode-picker__controls">
							<h2 className="dmyip-shortcode-picker__title">
								{ __(
									'Insert Dynamic Date',
									'dynamic-month-year-into-posts'
								) }
							</h2>
							<SearchControl
								className="dmyip-shortcode-picker__search"
								label={ __(
									'Search shortcodes',
									'dynamic-month-year-into-posts'
								) }
								placeholder={ __(
									'Search by name or shortcode',
									'dynamic-month-year-into-posts'
								) }
								value={ query }
								onChange={ setQuery }
							/>
							<p
								className="dmyip-shortcode-picker__results"
								aria-live="polite"
							>
								{ sprintf(
									/* translators: %d: number of matching shortcodes. */
									_n(
										'%d shortcode found',
										'%d shortcodes found',
										resultCount,
										'dynamic-month-year-into-posts'
									),
									resultCount
								) }
							</p>
						</div>
						{ filteredCategories.map( ( category ) => (
							<section
								className="dmyip-shortcode-picker__group"
								key={ category.label }
							>
								<h3 className="dmyip-shortcode-picker__heading">
									{ category.label }
								</h3>
								{ category.shortcodes.map( ( item ) => (
									<Button
										className="dmyip-shortcode-picker__item"
										key={ item.code }
										variant="tertiary"
										onClick={ () =>
											insertShortcode( item.code )
										}
									>
										<code>{ item.code }</code>
										<span>{ item.desc }</span>
									</Button>
								) ) }
							</section>
						) ) }
						{ resultCount === 0 && (
							<div className="dmyip-shortcode-picker__empty">
								<p>
									{ __(
										'No matching shortcodes.',
										'dynamic-month-year-into-posts'
									) }
								</p>
								<Button
									variant="secondary"
									onClick={ () => setQuery( '' ) }
								>
									{ __(
										'Clear search',
										'dynamic-month-year-into-posts'
									) }
								</Button>
							</div>
						) }
					</div>
				</Popover>
			) }
		</>
	);
}

registerFormatType( FORMAT_TYPE, {
	title: __( 'Dynamic Date', 'dynamic-month-year-into-posts' ),
	tagName: 'span',
	className: 'dmyip-shortcode',
	edit: DynamicDateFormatEdit,
} );

/**
 * Register presets in the native Block Bindings UI on WordPress 6.9+.
 *
 * WordPress 6.7 and 6.8 still use getValues for manually bound blocks.
 */
if ( typeof registerBlockBindingsSource === 'function' ) {
	registerBlockBindingsSource( {
		name: 'dmyip/date',
		getFieldsList() {
			return [
				{
					label: __(
						'Current Year',
						'dynamic-month-year-into-posts'
					),
					type: 'string',
					args: { type: 'year' },
				},
				{
					label: __(
						'Current Month',
						'dynamic-month-year-into-posts'
					),
					type: 'string',
					args: { type: 'month' },
				},
				{
					label: __(
						'Current Date',
						'dynamic-month-year-into-posts'
					),
					type: 'string',
					args: { type: 'date' },
				},
				{
					label: __(
						'Month and Year',
						'dynamic-month-year-into-posts'
					),
					type: 'string',
					args: { type: 'monthyear' },
				},
				{
					label: __( 'Weekday', 'dynamic-month-year-into-posts' ),
					type: 'string',
					args: { type: 'weekday' },
				},
				{
					label: __(
						'Black Friday',
						'dynamic-month-year-into-posts'
					),
					type: 'string',
					args: { type: 'blackfriday' },
				},
			];
		},
		getValues( { bindings } ) {
			return Object.fromEntries(
				Object.entries( bindings ).map( ( [ attribute, binding ] ) => {
					const type = binding.args?.type || 'year';
					return [
						attribute,
						editorData.bindingValues[ type ] ||
							editorData.bindingValues.year ||
							'',
					];
				} )
			);
		},
		canUserEditValue() {
			return false;
		},
	} );
}
