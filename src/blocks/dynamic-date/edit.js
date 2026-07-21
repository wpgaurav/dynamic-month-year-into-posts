/**
 * Dynamic Date block editor component.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

import metadata from './block.json';

const DATE_TYPES = [
	{
		value: 'year',
		label: __( 'Current Year', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'nyear',
		label: __( 'Next Year', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'pyear',
		label: __( 'Previous Year', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'month',
		label: __( 'Current Month', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'month_short',
		label: __( 'Current Month (Short)', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'month_number',
		label: __( 'Current Month (Number)', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'nmonth',
		label: __( 'Next Month', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'pmonth',
		label: __( 'Previous Month', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'date',
		label: __( 'Current Date', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'monthyear',
		label: __( 'Month and Year', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'day',
		label: __( 'Day of Month', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'weekday',
		label: __( 'Weekday', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'weekday_short',
		label: __( 'Weekday (Short)', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'published',
		label: __( 'Published Date', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'modified',
		label: __( 'Modified Date', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'blackfriday',
		label: __( 'Black Friday', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'cybermonday',
		label: __( 'Cyber Monday', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'daysuntil',
		label: __( 'Days Until Date', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'dayssince',
		label: __( 'Days Since Date', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'age',
		label: __( 'Age (Years)', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'age_ordinal',
		label: __( 'Age (Ordinal)', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'age_ym',
		label: __( 'Age (Years and Months)', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'age_ymd',
		label: __(
			'Age (Years, Months and Days)',
			'dynamic-month-year-into-posts'
		),
	},
	{
		value: 'season',
		label: __( 'Season (North)', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'season_south',
		label: __( 'Season (South)', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'nextoccurrence',
		label: __( 'Next Annual Occurrence', 'dynamic-month-year-into-posts' ),
	},
	{
		value: 'daysuntilnext',
		label: __(
			'Days Until Annual Occurrence',
			'dynamic-month-year-into-posts'
		),
	},
	{
		value: 'occurrenceyear',
		label: __( 'Annual Occurrence Year', 'dynamic-month-year-into-posts' ),
	},
];

/**
 * Edit component.
 *
 * @param {Object}   root0               Component props.
 * @param {Object}   root0.attributes    Block attributes.
 * @param {Function} root0.setAttributes Attribute updater.
 * @return {Element} Edit component.
 */
export default function Edit( { attributes, setAttributes } ) {
	const {
		type,
		format,
		offset,
		date,
		rule,
		case: textCase,
		rolloverDay,
	} = attributes;
	const blockProps = useBlockProps();
	const requiresDate = [
		'daysuntil',
		'dayssince',
		'age',
		'age_ordinal',
		'age_ym',
		'age_ymd',
		'nextoccurrence',
		'daysuntilnext',
		'occurrenceyear',
	].includes( type );
	const supportsRule = [
		'nextoccurrence',
		'daysuntilnext',
		'occurrenceyear',
	].includes( type );
	const supportsFormat = [
		'date',
		'published',
		'modified',
		'blackfriday',
		'cybermonday',
		'nextoccurrence',
	].includes( type );
	const supportsRollover = [
		'month',
		'month_short',
		'month_number',
		'monthyear',
	].includes( type );
	const dateLabel = type.startsWith( 'age' )
		? __( 'Birth Date', 'dynamic-month-year-into-posts' )
		: __( 'Date', 'dynamic-month-year-into-posts' );

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __(
						'Date Settings',
						'dynamic-month-year-into-posts'
					) }
				>
					<SelectControl
						label={ __(
							'Date Type',
							'dynamic-month-year-into-posts'
						) }
						value={ type }
						options={ DATE_TYPES }
						onChange={ ( value ) =>
							setAttributes( { type: value } )
						}
					/>

					{ type === 'year' && (
						<TextControl
							label={ __(
								'Year Offset',
								'dynamic-month-year-into-posts'
							) }
							value={ offset }
							onChange={ ( value ) =>
								setAttributes( {
									offset: Number.parseInt( value, 10 ) || 0,
								} )
							}
							type="number"
						/>
					) }

					{ supportsRollover && (
						<TextControl
							label={ __(
								'Rollover Day',
								'dynamic-month-year-into-posts'
							) }
							value={ rolloverDay || '' }
							onChange={ ( value ) =>
								setAttributes( {
									rolloverDay: Math.max(
										0,
										Math.min(
											31,
											Number.parseInt( value, 10 ) || 0
										)
									),
								} )
							}
							type="number"
							min="0"
							max="31"
							help={ __(
								'Use 20 to switch to the next month on the 20th. Use 0 to disable.',
								'dynamic-month-year-into-posts'
							) }
						/>
					) }

					{ requiresDate && (
						<TextControl
							label={ dateLabel }
							value={ date }
							onChange={ ( value ) =>
								setAttributes( { date: value } )
							}
							placeholder={
								supportsRule
									? 'MM-DD or YYYY-MM-DD'
									: 'YYYY-MM-DD'
							}
							help={
								supportsRule
									? __(
											'Use a month and day for an annual event, or enter a rule below.',
											'dynamic-month-year-into-posts'
									  )
									: undefined
							}
						/>
					) }

					{ supportsRule && (
						<TextControl
							label={ __(
								'Annual Rule',
								'dynamic-month-year-into-posts'
							) }
							value={ rule }
							onChange={ ( value ) =>
								setAttributes( { rule: value } )
							}
							placeholder="last sunday of january"
							help={ __(
								'A rule overrides the date. First, second, third, fourth and last weekdays are supported.',
								'dynamic-month-year-into-posts'
							) }
						/>
					) }

					{ supportsFormat && (
						<TextControl
							label={ __(
								'Date Format',
								'dynamic-month-year-into-posts'
							) }
							value={ format }
							onChange={ ( value ) =>
								setAttributes( { format: value } )
							}
							placeholder="F j, Y"
							help={ __(
								'PHP date format. Leave empty for the default.',
								'dynamic-month-year-into-posts'
							) }
						/>
					) }

					<SelectControl
						label={ __(
							'Text Case',
							'dynamic-month-year-into-posts'
						) }
						value={ textCase }
						options={ [
							{
								value: 'none',
								label: __(
									'Unchanged',
									'dynamic-month-year-into-posts'
								),
							},
							{
								value: 'title',
								label: __(
									'Title Case',
									'dynamic-month-year-into-posts'
								),
							},
							{
								value: 'upper',
								label: __(
									'UPPERCASE',
									'dynamic-month-year-into-posts'
								),
							},
							{
								value: 'lower',
								label: __(
									'lowercase',
									'dynamic-month-year-into-posts'
								),
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { case: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender
					block={ metadata.name }
					attributes={ attributes }
					EmptyResponsePlaceholder={ () =>
						__(
							'Complete the date settings to see a preview.',
							'dynamic-month-year-into-posts'
						)
					}
				/>
			</div>
		</>
	);
}
