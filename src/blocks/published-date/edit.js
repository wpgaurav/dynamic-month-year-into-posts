/**
 * Published Date block editor component.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';

/**
 * Edit component.
 * @param root0
 * @param root0.attributes
 * @param root0.setAttributes
 * @param root0.context
 */
export default function Edit( { attributes, setAttributes, context } ) {
	const { format, prefix, suffix } = attributes;
	const { postId, postType } = context;
	const blockProps = useBlockProps();

	// Get post date from entity.
	const [ date ] = useEntityProp( 'postType', postType, 'date', postId );

	// Format the date for preview.
	let previewText = __( '(Post date)', 'dynamic-month-year-into-posts' );
	if ( date ) {
		const dateObj = new Date( date );
		if ( ! isNaN( dateObj.getTime() ) ) {
			previewText = dateObj.toLocaleDateString( undefined, {
				year: 'numeric',
				month: 'long',
				day: 'numeric',
			} );
		}
	}

	const displayText = `${ prefix }${ previewText }${ suffix }`;

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __(
						'Date Settings',
						'dynamic-month-year-into-posts'
					) }
				>
					<TextControl
						label={ __(
							'Date Format',
							'dynamic-month-year-into-posts'
						) }
						value={ format }
						onChange={ ( newFormat ) =>
							setAttributes( { format: newFormat } )
						}
						placeholder="F j, Y"
						help={ __(
							'PHP date format. Leave empty for WordPress default.',
							'dynamic-month-year-into-posts'
						) }
					/>
					<TextControl
						label={ __(
							'Prefix',
							'dynamic-month-year-into-posts'
						) }
						value={ prefix }
						onChange={ ( newPrefix ) =>
							setAttributes( { prefix: newPrefix } )
						}
						placeholder="e.g., Published on "
					/>
					<TextControl
						label={ __(
							'Suffix',
							'dynamic-month-year-into-posts'
						) }
						value={ suffix }
						onChange={ ( newSuffix ) =>
							setAttributes( { suffix: newSuffix } )
						}
						placeholder="e.g., by Author"
					/>
				</PanelBody>
			</InspectorControls>

			<span { ...blockProps }>{ displayText }</span>
		</>
	);
}
