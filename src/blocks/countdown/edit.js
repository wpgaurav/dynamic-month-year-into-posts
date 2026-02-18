/**
 * Live Countdown block editor component.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

/**
 * Calculate days between two dates.
 * @param targetDate
 * @param mode
 */
function calculateDays( targetDate, mode ) {
	if ( ! targetDate ) {
		return 0;
	}

	const target = new Date( targetDate );
	const today = new Date();
	today.setHours( 0, 0, 0, 0 );
	target.setHours( 0, 0, 0, 0 );

	const diff =
		mode === 'until'
			? Math.floor( ( target - today ) / ( 1000 * 60 * 60 * 24 ) )
			: Math.floor( ( today - target ) / ( 1000 * 60 * 60 * 24 ) );

	return Math.max( 0, diff );
}

/**
 * Edit component.
 * @param root0
 * @param root0.attributes
 * @param root0.setAttributes
 */
export default function Edit( { attributes, setAttributes } ) {
	const { mode, targetDate, label, showLabel } = attributes;
	const blockProps = useBlockProps();

	const [ days, setDays ] = useState( calculateDays( targetDate, mode ) );

	// Update days when attributes change
	useEffect( () => {
		setDays( calculateDays( targetDate, mode ) );
	}, [ targetDate, mode ] );

	// Set up interval to update countdown in editor
	useEffect( () => {
		const interval = setInterval( () => {
			setDays( calculateDays( targetDate, mode ) );
		}, 60000 ); // Update every minute

		return () => clearInterval( interval );
	}, [ targetDate, mode ] );

	const displayText = showLabel ? `${ days } ${ label }` : String( days );

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __(
						'Countdown Settings',
						'dynamic-month-year-into-posts'
					) }
				>
					<SelectControl
						label={ __( 'Mode', 'dynamic-month-year-into-posts' ) }
						value={ mode }
						options={ [
							{
								value: 'until',
								label: __(
									'Days Until',
									'dynamic-month-year-into-posts'
								),
							},
							{
								value: 'since',
								label: __(
									'Days Since',
									'dynamic-month-year-into-posts'
								),
							},
						] }
						onChange={ ( newMode ) =>
							setAttributes( { mode: newMode } )
						}
					/>

					<TextControl
						label={ __(
							'Target Date',
							'dynamic-month-year-into-posts'
						) }
						value={ targetDate }
						onChange={ ( newDate ) =>
							setAttributes( { targetDate: newDate } )
						}
						placeholder="YYYY-MM-DD"
						type="date"
						help={ __(
							'Select or enter the target date.',
							'dynamic-month-year-into-posts'
						) }
					/>

					<ToggleControl
						label={ __(
							'Show Label',
							'dynamic-month-year-into-posts'
						) }
						checked={ showLabel }
						onChange={ ( newShowLabel ) =>
							setAttributes( { showLabel: newShowLabel } )
						}
					/>

					{ showLabel && (
						<TextControl
							label={ __(
								'Label Text',
								'dynamic-month-year-into-posts'
							) }
							value={ label }
							onChange={ ( newLabel ) =>
								setAttributes( { label: newLabel } )
							}
							placeholder="days"
						/>
					) }
				</PanelBody>

				<PanelBody
					title={ __( 'Preview', 'dynamic-month-year-into-posts' ) }
					initialOpen={ false }
				>
					<p>
						<strong>
							{ __(
								'Current countdown:',
								'dynamic-month-year-into-posts'
							) }
						</strong>
					</p>
					<p style={ { fontSize: '2em', fontWeight: 'bold' } }>
						{ displayText }
					</p>
					<p style={ { fontSize: '0.85em', color: '#757575' } }>
						{ __(
							'This countdown will update live on the frontend.',
							'dynamic-month-year-into-posts'
						) }
					</p>
				</PanelBody>
			</InspectorControls>

			<span { ...blockProps }>
				{ targetDate
					? displayText
					: __(
							'Set a target date',
							'dynamic-month-year-into-posts'
					  ) }
			</span>
		</>
	);
}
