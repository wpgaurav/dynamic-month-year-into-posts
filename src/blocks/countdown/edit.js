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
import ServerSideRender from '@wordpress/server-side-render';

import metadata from './block.json';

/**
 * Edit component.
 *
 * @param {Object}   root0               Component props.
 * @param {Object}   root0.attributes    Block attributes.
 * @param {Function} root0.setAttributes Attribute updater.
 * @return {Element} Edit component.
 */
export default function Edit( { attributes, setAttributes } ) {
	const { mode, targetDate, label, showLabel, recurring } = attributes;
	const blockProps = useBlockProps();

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
							setAttributes( {
								mode: newMode,
								recurring:
									newMode === 'since' ? false : recurring,
							} )
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
						type="date"
						help={ __(
							'The site timezone is used on the frontend.',
							'dynamic-month-year-into-posts'
						) }
					/>

					{ mode === 'until' && (
						<ToggleControl
							label={ __(
								'Repeat every year',
								'dynamic-month-year-into-posts'
							) }
							help={ __(
								'Uses the selected month and day, then rolls over after the event.',
								'dynamic-month-year-into-posts'
							) }
							checked={ recurring }
							onChange={ ( value ) =>
								setAttributes( { recurring: value } )
							}
						/>
					) }

					<ToggleControl
						label={ __(
							'Show Label',
							'dynamic-month-year-into-posts'
						) }
						checked={ showLabel }
						onChange={ ( value ) =>
							setAttributes( { showLabel: value } )
						}
					/>

					{ showLabel && (
						<TextControl
							label={ __(
								'Label Text',
								'dynamic-month-year-into-posts'
							) }
							value={ label }
							onChange={ ( value ) =>
								setAttributes( { label: value } )
							}
						/>
					) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender
					block={ metadata.name }
					attributes={ attributes }
					EmptyResponsePlaceholder={ () =>
						__(
							'Set a valid target date.',
							'dynamic-month-year-into-posts'
						)
					}
				/>
			</div>
		</>
	);
}
