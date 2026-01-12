/**
 * Dynamic Month & Year into Posts - Block Editor Integration
 *
 * Main entry point for the block editor scripts.
 */

// Register blocks
import './blocks/dynamic-date';
import './blocks/countdown';

// Editor styles
import './editor.css';

// Register the toolbar format type for inserting shortcodes into RichText
import { registerFormatType, insert, create, applyFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { Popover, Button } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { calendar } from '@wordpress/icons';

// Format type name
const FORMAT_TYPE = 'dmyip/shortcode';

/**
 * Calculate preview value for a shortcode.
 *
 * @param {string} shortcode The shortcode text.
 * @return {string} The preview value.
 */
function getShortcodePreview( shortcode ) {
	const now = new Date();
	const shortcodeLower = shortcode.toLowerCase();

	// Year shortcodes
	if ( shortcodeLower === '[year]' ) {
		return String( now.getFullYear() );
	}
	if ( shortcodeLower === '[nyear]' ) {
		return String( now.getFullYear() + 1 );
	}
	if ( shortcodeLower === '[pyear]' ) {
		return String( now.getFullYear() - 1 );
	}

	// Month shortcodes
	if ( shortcodeLower === '[month]' ) {
		return now.toLocaleDateString( undefined, { month: 'long' } );
	}
	if ( shortcodeLower === '[mon]' ) {
		return now.toLocaleDateString( undefined, { month: 'short' } );
	}
	if ( shortcodeLower === '[nmonth]' ) {
		const next = new Date( now.getFullYear(), now.getMonth() + 1, 1 );
		return next.toLocaleDateString( undefined, { month: 'long' } );
	}
	if ( shortcodeLower === '[pmonth]' ) {
		const prev = new Date( now.getFullYear(), now.getMonth() - 1, 1 );
		return prev.toLocaleDateString( undefined, { month: 'long' } );
	}

	// Date shortcodes
	if ( shortcodeLower === '[date]' ) {
		return now.toLocaleDateString( undefined, { month: 'long', day: 'numeric', year: 'numeric' } );
	}
	if ( shortcodeLower === '[monthyear]' ) {
		return now.toLocaleDateString( undefined, { month: 'long', year: 'numeric' } );
	}
	if ( shortcodeLower === '[dt]' ) {
		return String( now.getDate() );
	}
	if ( shortcodeLower === '[weekday]' ) {
		return now.toLocaleDateString( undefined, { weekday: 'long' } );
	}

	// Post dates (can't preview these)
	if ( shortcodeLower === '[datepublished]' || shortcodeLower === '[datemodified]' ) {
		return '(post date)';
	}

	// Events
	if ( shortcodeLower === '[blackfriday]' ) {
		return getBlackFriday( now.getFullYear() );
	}
	if ( shortcodeLower === '[cybermonday]' ) {
		return getCyberMonday( now.getFullYear() );
	}

	// Countdown - extract date attribute
	const daysUntilMatch = shortcode.match( /\[daysuntil\s+date="([^"]+)"\]/i );
	if ( daysUntilMatch && daysUntilMatch[ 1 ] ) {
		return getDaysUntil( daysUntilMatch[ 1 ] );
	}

	const daysSinceMatch = shortcode.match( /\[dayssince\s+date="([^"]+)"\]/i );
	if ( daysSinceMatch && daysSinceMatch[ 1 ] ) {
		return getDaysSince( daysSinceMatch[ 1 ] );
	}

	// Age - extract date and format attributes
	const ageMatch = shortcode.match( /\[age\s+date="([^"]*)"(?:\s+format="([^"]*)")?\]/i );
	if ( ageMatch ) {
		const date = ageMatch[ 1 ];
		const format = ageMatch[ 2 ] || 'y';
		if ( date ) {
			return getAge( date, format );
		}
		return '(set date)';
	}

	// Shortcodes with empty dates
	if ( shortcodeLower.includes( '[daysuntil' ) || shortcodeLower.includes( '[dayssince' ) ) {
		return '(set date)';
	}

	// Default: return the shortcode itself
	return shortcode;
}

/**
 * Get Black Friday date string.
 */
function getBlackFriday( year ) {
	const nov = new Date( year, 10, 1 );
	while ( nov.getDay() !== 4 ) {
		nov.setDate( nov.getDate() + 1 );
	}
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
 * Calculate age from a birth date.
 */
function getAge( dateStr, format = 'y' ) {
	const birth = new Date( dateStr );
	const today = new Date();

	if ( isNaN( birth.getTime() ) ) {
		return '';
	}

	let years = today.getFullYear() - birth.getFullYear();
	let months = today.getMonth() - birth.getMonth();
	let days = today.getDate() - birth.getDate();

	if ( days < 0 ) {
		months--;
		const prevMonth = new Date( today.getFullYear(), today.getMonth(), 0 );
		days += prevMonth.getDate();
	}

	if ( months < 0 ) {
		years--;
		months += 12;
	}

	switch ( format ) {
		case 'ymd': {
			const parts = [];
			if ( years > 0 ) parts.push( `${ years } year${ years !== 1 ? 's' : '' }` );
			if ( months > 0 ) parts.push( `${ months } month${ months !== 1 ? 's' : '' }` );
			if ( days > 0 ) parts.push( `${ days } day${ days !== 1 ? 's' : '' }` );
			return parts.join( ', ' ) || '0 days';
		}
		case 'ym': {
			const parts = [];
			if ( years > 0 ) parts.push( `${ years } year${ years !== 1 ? 's' : '' }` );
			if ( months > 0 ) parts.push( `${ months } month${ months !== 1 ? 's' : '' }` );
			return parts.join( ', ' ) || '0 months';
		}
		case 'y':
		default:
			return String( years );
	}
}

/**
 * Shortcode categories for the toolbar dropdown.
 */
const SHORTCODE_CATEGORIES = [
	{
		label: __( 'Year', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{ code: '[year]', desc: __( 'Current year', 'dynamic-month-year-into-posts' ) },
			{ code: '[nyear]', desc: __( 'Next year', 'dynamic-month-year-into-posts' ) },
			{ code: '[pyear]', desc: __( 'Previous year', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Month', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{ code: '[month]', desc: __( 'Current month', 'dynamic-month-year-into-posts' ) },
			{ code: '[mon]', desc: __( 'Month (short)', 'dynamic-month-year-into-posts' ) },
			{ code: '[nmonth]', desc: __( 'Next month', 'dynamic-month-year-into-posts' ) },
			{ code: '[pmonth]', desc: __( 'Previous month', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Date', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{ code: '[date]', desc: __( "Today's date", 'dynamic-month-year-into-posts' ) },
			{ code: '[monthyear]', desc: __( 'Month and year', 'dynamic-month-year-into-posts' ) },
			{ code: '[dt]', desc: __( 'Day of month', 'dynamic-month-year-into-posts' ) },
			{ code: '[weekday]', desc: __( 'Day of week', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Post Dates', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{ code: '[datepublished]', desc: __( 'Publication date', 'dynamic-month-year-into-posts' ) },
			{ code: '[datemodified]', desc: __( 'Modified date', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Events', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{ code: '[blackfriday]', desc: __( 'Black Friday', 'dynamic-month-year-into-posts' ) },
			{ code: '[cybermonday]', desc: __( 'Cyber Monday', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Countdown', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{ code: '[daysuntil date=""]', desc: __( 'e.g. date="2025-12-25"', 'dynamic-month-year-into-posts' ) },
			{ code: '[dayssince date=""]', desc: __( 'e.g. date="2020-01-01"', 'dynamic-month-year-into-posts' ) },
		],
	},
	{
		label: __( 'Age', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{ code: '[age date=""]', desc: __( 'e.g. date="1990-05-15"', 'dynamic-month-year-into-posts' ) },
			{ code: '[age date="" format="ym"]', desc: __( '34 years, 7 months', 'dynamic-month-year-into-posts' ) },
			{ code: '[age date="" format="ymd"]', desc: __( '34 years, 7 months, 12 days', 'dynamic-month-year-into-posts' ) },
		],
	},
];

/**
 * Update shortcode previews in the editor.
 * This function finds all shortcode spans and updates their data-preview attribute.
 */
function updateShortcodePreviews() {
	const shortcodeSpans = document.querySelectorAll( '.dmyip-shortcode' );
	shortcodeSpans.forEach( ( span ) => {
		const shortcodeText = span.textContent;
		const preview = getShortcodePreview( shortcodeText );
		span.setAttribute( 'data-shortcode', shortcodeText );
		span.setAttribute( 'data-preview', preview );
	} );
}

/**
 * Format type edit component for the toolbar button.
 */
function DynamicDateFormatEdit( { value, onChange, isActive } ) {
	const [ isOpen, setIsOpen ] = useState( false );

	// Update previews when content changes
	useEffect( () => {
		// Small delay to let DOM update
		const timer = setTimeout( updateShortcodePreviews, 100 );
		return () => clearTimeout( timer );
	}, [ value ] );

	const togglePopover = () => setIsOpen( ! isOpen );

	const insertShortcode = ( shortcode ) => {
		// Create the shortcode text
		const toInsert = create( { text: shortcode } );

		// Apply the format to highlight it
		const formattedValue = applyFormat( toInsert, {
			type: FORMAT_TYPE,
		}, 0, shortcode.length );

		// Insert the formatted shortcode
		onChange( insert( value, formattedValue ) );
		setIsOpen( false );

		// Update previews after insertion
		setTimeout( updateShortcodePreviews, 150 );
	};

	return (
		<>
			<RichTextToolbarButton
				icon={ calendar }
				title={ __( 'Insert Dynamic Date', 'dynamic-month-year-into-posts' ) }
				onClick={ togglePopover }
				isActive={ isOpen }
			/>
			{ isOpen && (
				<Popover
					position="bottom center"
					onClose={ () => setIsOpen( false ) }
					focusOnMount="container"
				>
					<div
						style={ {
							padding: '12px',
							minWidth: '240px',
							maxHeight: '350px',
							overflowY: 'auto',
						} }
					>
						<div
							style={ {
								fontWeight: '600',
								marginBottom: '12px',
								paddingBottom: '8px',
								borderBottom: '1px solid #ddd',
							} }
						>
							{ __( 'Insert Dynamic Date', 'dynamic-month-year-into-posts' ) }
						</div>
						{ SHORTCODE_CATEGORIES.map( ( category, catIndex ) => (
							<div key={ `cat-${ catIndex }` } style={ { marginBottom: '10px' } }>
								<div
									style={ {
										fontSize: '11px',
										fontWeight: '600',
										textTransform: 'uppercase',
										color: '#757575',
										marginBottom: '4px',
									} }
								>
									{ category.label }
								</div>
								{ category.shortcodes.map( ( item, itemIndex ) => (
									<Button
										key={ `item-${ catIndex }-${ itemIndex }` }
										variant="tertiary"
										onClick={ () => insertShortcode( item.code ) }
										style={ {
											display: 'flex',
											width: '100%',
											justifyContent: 'space-between',
											padding: '4px 8px',
											height: 'auto',
											marginBottom: '2px',
										} }
									>
										<code
											style={ {
												fontSize: '11px',
												background: '#f0f0f0',
												padding: '2px 4px',
												borderRadius: '2px',
											} }
										>
											{ item.code }
										</code>
										<span
											style={ {
												fontSize: '11px',
												color: '#757575',
											} }
										>
											{ item.desc }
										</span>
									</Button>
								) ) }
							</div>
						) ) }
					</div>
				</Popover>
			) }
		</>
	);
}

/**
 * Register the format type for the toolbar.
 */
registerFormatType( FORMAT_TYPE, {
	title: __( 'Dynamic Date', 'dynamic-month-year-into-posts' ),
	tagName: 'span',
	className: 'dmyip-shortcode',
	edit: DynamicDateFormatEdit,
} );

// Initialize previews on page load
if ( typeof window !== 'undefined' ) {
	// Update previews when editor is ready
	window.addEventListener( 'load', () => {
		setTimeout( updateShortcodePreviews, 500 );
	} );

	// Also observe for new shortcode elements
	const observer = new MutationObserver( ( mutations ) => {
		let hasNewShortcodes = false;
		mutations.forEach( ( mutation ) => {
			if ( mutation.addedNodes.length ) {
				mutation.addedNodes.forEach( ( node ) => {
					if ( node.nodeType === 1 ) {
						if ( node.classList?.contains( 'dmyip-shortcode' ) ||
							 node.querySelector?.( '.dmyip-shortcode' ) ) {
							hasNewShortcodes = true;
						}
					}
				} );
			}
		} );
		if ( hasNewShortcodes ) {
			updateShortcodePreviews();
		}
	} );

	// Start observing when DOM is ready
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', () => {
			const editorRoot = document.querySelector( '.editor-styles-wrapper' ) || document.body;
			observer.observe( editorRoot, { childList: true, subtree: true } );
		} );
	} else {
		const editorRoot = document.querySelector( '.editor-styles-wrapper' ) || document.body;
		observer.observe( editorRoot, { childList: true, subtree: true } );
	}
}
