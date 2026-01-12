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
import { registerFormatType, insert, create } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { Popover, Button } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { calendar } from '@wordpress/icons';

// Format type name
const FORMAT_TYPE = 'dmyip/shortcode';

/**
 * Shortcode categories for the toolbar dropdown.
 */
const SHORTCODE_CATEGORIES = [
	{
		label: __( 'Year', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{
				code: '[year]',
				desc: __( 'Current year', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[nyear]',
				desc: __( 'Next year', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[pyear]',
				desc: __( 'Previous year', 'dynamic-month-year-into-posts' ),
			},
		],
	},
	{
		label: __( 'Month', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{
				code: '[month]',
				desc: __( 'Current month', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[mon]',
				desc: __( 'Month (short)', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[nmonth]',
				desc: __( 'Next month', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[pmonth]',
				desc: __( 'Previous month', 'dynamic-month-year-into-posts' ),
			},
		],
	},
	{
		label: __( 'Date', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{
				code: '[date]',
				desc: __( "Today's date", 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[monthyear]',
				desc: __( 'Month and year', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[dt]',
				desc: __( 'Day of month', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[weekday]',
				desc: __( 'Day of week', 'dynamic-month-year-into-posts' ),
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
		label: __( 'Events', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{
				code: '[blackfriday]',
				desc: __( 'Black Friday', 'dynamic-month-year-into-posts' ),
			},
			{
				code: '[cybermonday]',
				desc: __( 'Cyber Monday', 'dynamic-month-year-into-posts' ),
			},
		],
	},
	{
		label: __( 'Countdown', 'dynamic-month-year-into-posts' ),
		shortcodes: [
			{
				code: '[daysuntil date=""]',
				desc: __(
					'e.g. date="2025-12-25"',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[dayssince date=""]',
				desc: __(
					'e.g. date="2020-01-01"',
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
					'e.g. date="1990-05-15"',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[age date="" format="ym"]',
				desc: __(
					'34 years, 7 months',
					'dynamic-month-year-into-posts'
				),
			},
			{
				code: '[age date="" format="ymd"]',
				desc: __(
					'34 years, 7 months, 12 days',
					'dynamic-month-year-into-posts'
				),
			},
		],
	},
];

/**
 * Format type edit component for the toolbar button.
 *
 * @param {Object} props          Component props.
 * @param {Object} props.value    RichText value.
 * @param {Function} props.onChange RichText onChange handler.
 */
function DynamicDateFormatEdit( { value, onChange } ) {
	const [ isOpen, setIsOpen ] = useState( false );

	const togglePopover = () => setIsOpen( ! isOpen );

	const insertShortcode = ( shortcode ) => {
		// Insert plain text shortcode without any formatting
		onChange( insert( value, create( { text: shortcode } ) ) );
		setIsOpen( false );
	};

	return (
		<>
			<RichTextToolbarButton
				icon={ calendar }
				title={ __(
					'Insert Dynamic Date',
					'dynamic-month-year-into-posts'
				) }
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
							{ __(
								'Insert Dynamic Date',
								'dynamic-month-year-into-posts'
							) }
						</div>
						{ SHORTCODE_CATEGORIES.map( ( category, catIndex ) => (
							<div
								key={ `cat-${ catIndex }` }
								style={ { marginBottom: '10px' } }
							>
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
								{ category.shortcodes.map(
									( item, itemIndex ) => (
										<Button
											key={ `item-${ catIndex }-${ itemIndex }` }
											variant="tertiary"
											onClick={ () =>
												insertShortcode( item.code )
											}
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
									)
								) }
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
