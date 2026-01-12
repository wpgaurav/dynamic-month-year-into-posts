/**
 * Dynamic Date block editor component.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	TextControl,
	__experimentalNumberControl as NumberControl,
} from '@wordpress/components';

/**
 * Date type options organized by category.
 */
const DATE_TYPES = [
	{
		label: __( 'Year', 'dynamic-month-year-into-posts' ),
		options: [
			{ value: 'year', label: __( 'Current Year', 'dynamic-month-year-into-posts' ) },
			{ value: 'nyear', label: __( 'Next Year', 'dynamic-month-year-into-posts' ) },
			{ value: 'pyear', label: __( 'Previous Year', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Month', 'dynamic-month-year-into-posts' ),
		options: [
			{ value: 'month', label: __( 'Current Month (Full)', 'dynamic-month-year-into-posts' ) },
			{ value: 'month_short', label: __( 'Current Month (Short)', 'dynamic-month-year-into-posts' ) },
			{ value: 'month_number', label: __( 'Current Month (Number)', 'dynamic-month-year-into-posts' ) },
			{ value: 'nmonth', label: __( 'Next Month', 'dynamic-month-year-into-posts' ) },
			{ value: 'pmonth', label: __( 'Previous Month', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Date', 'dynamic-month-year-into-posts' ),
		options: [
			{ value: 'date', label: __( 'Full Date', 'dynamic-month-year-into-posts' ) },
			{ value: 'monthyear', label: __( 'Month and Year', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Day', 'dynamic-month-year-into-posts' ),
		options: [
			{ value: 'day', label: __( 'Day of Month', 'dynamic-month-year-into-posts' ) },
			{ value: 'weekday', label: __( 'Weekday (Full)', 'dynamic-month-year-into-posts' ) },
			{ value: 'weekday_short', label: __( 'Weekday (Short)', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Post Dates', 'dynamic-month-year-into-posts' ),
		options: [
			{ value: 'published', label: __( 'Published Date', 'dynamic-month-year-into-posts' ) },
			{ value: 'modified', label: __( 'Modified Date', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Events', 'dynamic-month-year-into-posts' ),
		options: [
			{ value: 'blackfriday', label: __( 'Black Friday', 'dynamic-month-year-into-posts' ) },
			{ value: 'cybermonday', label: __( 'Cyber Monday', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Countdown', 'dynamic-month-year-into-posts' ),
		options: [
			{ value: 'daysuntil', label: __( 'Days Until Date', 'dynamic-month-year-into-posts' ) },
			{ value: 'dayssince', label: __( 'Days Since Date', 'dynamic-month-year-into-posts' ) },
		],
	},
];

/**
 * Get preview text for a date type.
 *
 * @param {string} type   Date type.
 * @param {number} offset Year offset.
 * @param {string} date   Target date.
 * @return {string} Preview text.
 */
function getPreviewText( type, offset, date ) {
	const now = new Date();

	switch ( type ) {
		case 'year':
			return String( now.getFullYear() + offset );
		case 'nyear':
			return String( now.getFullYear() + 1 );
		case 'pyear':
			return String( now.getFullYear() - 1 );
		case 'month':
			return now.toLocaleDateString( undefined, { month: 'long' } );
		case 'month_short':
			return now.toLocaleDateString( undefined, { month: 'short' } );
		case 'month_number':
			return String( now.getMonth() + 1 );
		case 'nmonth':
			const nextMonth = new Date( now.getFullYear(), now.getMonth() + 1, 1 );
			return nextMonth.toLocaleDateString( undefined, { month: 'long' } );
		case 'pmonth':
			const prevMonth = new Date( now.getFullYear(), now.getMonth() - 1, 1 );
			return prevMonth.toLocaleDateString( undefined, { month: 'long' } );
		case 'date':
			return now.toLocaleDateString( undefined, { month: 'long', day: 'numeric', year: 'numeric' } );
		case 'monthyear':
			return now.toLocaleDateString( undefined, { month: 'long', year: 'numeric' } );
		case 'day':
			return String( now.getDate() );
		case 'weekday':
			return now.toLocaleDateString( undefined, { weekday: 'long' } );
		case 'weekday_short':
			return now.toLocaleDateString( undefined, { weekday: 'short' } );
		case 'published':
		case 'modified':
			return __( '(Post date)', 'dynamic-month-year-into-posts' );
		case 'blackfriday':
			return getBlackFriday( now.getFullYear() );
		case 'cybermonday':
			return getCyberMonday( now.getFullYear() );
		case 'daysuntil':
			if ( ! date ) return __( 'Set date', 'dynamic-month-year-into-posts' );
			return getDaysUntil( date );
		case 'dayssince':
			if ( ! date ) return __( 'Set date', 'dynamic-month-year-into-posts' );
			return getDaysSince( date );
		default:
			return String( now.getFullYear() );
	}
}

/**
 * Get Black Friday date string.
 */
function getBlackFriday( year ) {
	// Fourth Thursday of November + 1 day
	const nov = new Date( year, 10, 1 ); // November
	let thursday = 1;
	while ( nov.getDay() !== 4 ) {
		nov.setDate( nov.getDate() + 1 );
	}
	// Find 4th Thursday
	const fourthThursday = new Date( year, 10, nov.getDate() + 21 );
	const blackFriday = new Date( fourthThursday );
	blackFriday.setDate( blackFriday.getDate() + 1 );
	return blackFriday.toLocaleDateString( undefined, { month: 'long', day: 'numeric' } );
}

/**
 * Get Cyber Monday date string.
 */
function getCyberMonday( year ) {
	const nov = new Date( year, 10, 1 );
	while ( nov.getDay() !== 4 ) {
		nov.setDate( nov.getDate() + 1 );
	}
	const fourthThursday = new Date( year, 10, nov.getDate() + 21 );
	const cyberMonday = new Date( fourthThursday );
	cyberMonday.setDate( cyberMonday.getDate() + 4 );
	return cyberMonday.toLocaleDateString( undefined, { month: 'long', day: 'numeric' } );
}

/**
 * Calculate days until a date.
 */
function getDaysUntil( dateStr ) {
	const target = new Date( dateStr );
	const today = new Date();
	today.setHours( 0, 0, 0, 0 );
	target.setHours( 0, 0, 0, 0 );
	const diff = Math.floor( ( target - today ) / ( 1000 * 60 * 60 * 24 ) );
	return String( Math.max( 0, diff ) );
}

/**
 * Calculate days since a date.
 */
function getDaysSince( dateStr ) {
	const target = new Date( dateStr );
	const today = new Date();
	today.setHours( 0, 0, 0, 0 );
	target.setHours( 0, 0, 0, 0 );
	const diff = Math.floor( ( today - target ) / ( 1000 * 60 * 60 * 24 ) );
	return String( Math.max( 0, diff ) );
}

/**
 * Edit component.
 */
export default function Edit( { attributes, setAttributes } ) {
	const { type, format, offset, date } = attributes;
	const blockProps = useBlockProps();

	const previewText = getPreviewText( type, offset, date );
	const showOffsetControl = type === 'year';
	const showDateControl = type === 'daysuntil' || type === 'dayssince';
	const showFormatControl = type === 'date';

	// Flatten options for SelectControl
	const flatOptions = DATE_TYPES.reduce( ( acc, group ) => {
		acc.push( { value: '', label: `— ${ group.label } —`, disabled: true } );
		return acc.concat( group.options );
	}, [] );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Date Settings', 'dynamic-month-year-into-posts' ) }>
					<SelectControl
						label={ __( 'Date Type', 'dynamic-month-year-into-posts' ) }
						value={ type }
						options={ flatOptions }
						onChange={ ( newType ) => setAttributes( { type: newType } ) }
					/>

					{ showOffsetControl && (
						<NumberControl
							label={ __( 'Year Offset', 'dynamic-month-year-into-posts' ) }
							value={ offset }
							onChange={ ( newOffset ) =>
								setAttributes( { offset: parseInt( newOffset, 10 ) || 0 } )
							}
							help={ __( 'Add or subtract years from current year.', 'dynamic-month-year-into-posts' ) }
						/>
					) }

					{ showDateControl && (
						<TextControl
							label={ __( 'Target Date', 'dynamic-month-year-into-posts' ) }
							value={ date }
							onChange={ ( newDate ) => setAttributes( { date: newDate } ) }
							placeholder="YYYY-MM-DD"
							help={ __( 'Enter date in YYYY-MM-DD format.', 'dynamic-month-year-into-posts' ) }
						/>
					) }

					{ showFormatControl && (
						<TextControl
							label={ __( 'Custom Format', 'dynamic-month-year-into-posts' ) }
							value={ format }
							onChange={ ( newFormat ) => setAttributes( { format: newFormat } ) }
							placeholder="F j, Y"
							help={ __( 'PHP date format. Leave empty for default.', 'dynamic-month-year-into-posts' ) }
						/>
					) }
				</PanelBody>

				<PanelBody title={ __( 'Preview', 'dynamic-month-year-into-posts' ) } initialOpen={ false }>
					<p>
						<strong>{ __( 'Current output:', 'dynamic-month-year-into-posts' ) }</strong>
					</p>
					<p style={ { fontSize: '1.5em', fontWeight: 'bold' } }>{ previewText }</p>
					<p style={ { fontSize: '0.85em', color: '#757575' } }>
						{ __( 'This is a live preview. Actual output may vary based on server settings.', 'dynamic-month-year-into-posts' ) }
					</p>
				</PanelBody>
			</InspectorControls>

			<span { ...blockProps }>{ previewText }</span>
		</>
	);
}
